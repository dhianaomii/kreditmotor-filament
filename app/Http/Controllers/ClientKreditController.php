<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanKredit;
use App\Models\MetodePembayaran;
use App\Models\Kredit;
use App\Models\Motor;
use App\Models\JenisCicilan;
use App\Models\Pengirimans;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Events\KreditDibatalkan;
use Midtrans\Config;
use Midtrans\Snap;

class ClientKreditController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Validasi server_key
        if (empty(Config::$serverKey)) {
            Log::error('Midtrans server key is not set in configuration.');
            throw new \Exception('Midtrans server key is not set.');
        }

        // Log konfigurasi untuk debugging
        Log::info('Midtrans Config', [
            'server_key' => Config::$serverKey,
            'is_production' => Config::$isProduction,
        ]);
    }

    /**
     * Mendapatkan Snap Token untuk pembayaran DP
     */
    public function getSnapToken(Request $request, $pengajuanId)
    {
        Log::info('Starting getSnapToken', ['pengajuanId' => $pengajuanId]);

        try {
            // Ambil data pengajuan
            $pengajuan = PengajuanKredit::with(['Motor', 'JenisCicilan'])
                ->where('pelanggan_id', Auth::guard('pelanggan')->id())
                ->find($pengajuanId);

            if (!$pengajuan) {
                Log::error('Pengajuan not found', ['pengajuanId' => $pengajuanId]);
                return response()->json(['error' => 'Pengajuan tidak ditemukan.'], 404);
            }

            Log::info('Pengajuan fetched', ['pengajuan' => $pengajuan->toArray()]);

            // Validasi status pengajuan
            if (!in_array($pengajuan->status_pengajuan, ['Diproses', 'Menunggu Pembayaran'])) {
                Log::warning('Invalid status', ['status' => $pengajuan->status_pengajuan]);
                return response()->json(['error' => 'Pengajuan ini tidak dapat dibayar karena statusnya bukan "Diproses" atau "Menunggu Pembayaran".'], 400);
            }

            // Validasi data pembayaran
            if (!$pengajuan->dp || $pengajuan->dp <= 0) {
                Log::error('Invalid DP amount', ['dp' => $pengajuan->dp]);
                return response()->json(['error' => 'Jumlah DP tidak valid.'], 400);
            }

            // Ambil data pelanggan
            $pelanggan = Auth::guard('pelanggan')->user();
            if (!$pelanggan) {
                Log::error('Pelanggan not authenticated');
                return response()->json(['error' => 'Pelanggan tidak terautentikasi.'], 401);
            }

            if (!$pelanggan->email) {
                Log::error('Missing pelanggan email', ['pelanggan_id' => $pelanggan->id]);
                return response()->json(['error' => 'Email pelanggan tidak ditemukan.'], 400);
            }

            // Ambil tanggal mulai kredit dari request
            $tglMulaiKredit = $request->input('tgl_mulai_kredit');
            if (!$tglMulaiKredit) {
                Log::error('Tanggal mulai kredit tidak diberikan');
                return response()->json(['error' => 'Tanggal mulai kredit diperlukan.'], 400);
            }

            $tglMulai = Carbon::parse($tglMulaiKredit);
            $tglSelesai = $tglMulai->copy()->addMonths($pengajuan->JenisCicilan->lama_cicilan ?? 0);

            // Ambil data alamat dari request
            $alamatId = $request->input('alamat_id');
            $newAlamat = $request->input('new_alamat');
            $newKota = $request->input('new_kota');
            $newProvinsi = $request->input('new_provinsi');
            $newKodePos = $request->input('new_kode_pos');

            // Simpan atau perbarui alamat ke tabel pelanggan
            if ($alamatId === 'new' && $newAlamat && $newKota && $newProvinsi && $newKodePos) {
                Log::info('New address submitted', ['new_alamat' => $newAlamat, 'new_kota' => $newKota, 'new_provinsi' => $newProvinsi, 'new_kode_pos' => $newKodePos]);
                for ($i = 1; $i <= 3; $i++) {
                    if (!$pelanggan->{"alamat$i"} || !$pelanggan->{"kota$i"} || !$pelanggan->{"provinsi$i"} || !$pelanggan->{"kode_pos$i"}) {
                        $pelanggan->{"alamat$i"} = $newAlamat;
                        $pelanggan->{"kota$i"} = $newKota;
                        $pelanggan->{"provinsi$i"} = $newProvinsi;
                        $pelanggan->{"kode_pos$i"} = $newKodePos;
                        $pelanggan->save();
                        Log::info('New address saved', ['pelanggan_id' => $pelanggan->id, 'alamat_slot' => $i]);
                        break;
                    }
                }
            } elseif ($alamatId && $alamatId >= 1 && $alamatId <= 3) {
                Log::info('Existing address selected', ['alamat_id' => $alamatId]);
            }

            // Tentukan alamat untuk Midtrans
            $address = $alamatId && $alamatId >= 1 && $alamatId <= 3
                ? $pelanggan->{"alamat$alamatId"}
                : ($newAlamat ?? '');
            $city = $alamatId && $alamatId >= 1 && $alamatId <= 3
                ? $pelanggan->{"kota$alamatId"}
                : ($newKota ?? '');
            $postalCode = $alamatId && $alamatId >= 1 && $alamatId <= 3
                ? $pelanggan->{"kode_pos$alamatId"}
                : ($newKodePos ?? '');
            $province = $alamatId && $alamatId >= 1 && $alamatId <= 3
                ? $pelanggan->{"provinsi$alamatId"}
                : ($newProvinsi ?? '');

            // Gunakan pengajuan_kredit_id sebagai bagian dari order_id
            $orderId = 'DP-' . $pengajuan->id . '-' . time();
            Log::info('Order ID generated', ['orderId' => $orderId]);

            // Cek atau buat data kredit
            $kredit = Kredit::where('pengajuan_kredit_id', $pengajuan->id)->first();
            if ($kredit) {
                $updateResult = $kredit->update([
                    'order_id' => $orderId,
                    'tgl_mulai_kredit' => $tglMulai,
                    'tgl_selesai_kredit' => $tglSelesai,
                    'sisa_kredit' => $pengajuan->harga_kredit - $pengajuan->dp,
                    'status_kredit' => 'Dicicil',
                    'metode_pembayaran_id' => 1, // Placeholder sementara
                ]);
                if ($updateResult) {
                    Log::info('Kredit updated successfully', ['kredit_id' => $kredit->id, 'order_id' => $orderId]);
                } else {
                    Log::error('Failed to update kredit', ['kredit_id' => $kredit->id]);
                    return response()->json(['error' => 'Gagal memperbarui data kredit.'], 500);
                }
            } else {
                $kredit = Kredit::create([
                    'pengajuan_kredit_id' => $pengajuan->id,
                    'metode_pembayaran_id' => 1, // Placeholder sementara
                    'status_kredit' => 'Dicicil',
                    'sisa_kredit' => $pengajuan->harga_kredit - $pengajuan->dp,
                    'payment_status' => 'pending',
                    'order_id' => $orderId,
                    'tgl_mulai_kredit' => $tglMulai,
                    'tgl_selesai_kredit' => $tglSelesai,
                ]);
                Log::info('Kredit created', ['kredit_id' => $kredit->id]);
            }

            // Parameter untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $pengajuan->dp,
                ],
                'item_details' => [
                    [
                        'id' => $pengajuan->motor_id,
                        'price' => $pengajuan->dp,
                        'quantity' => 1,
                        'name' => $pengajuan->Motor->nama_motor ?? 'Pembayaran DP',
                    ],
                ],
                'customer_details' => [
                    'first_name' => $pelanggan->nama_pelanggan,
                    'email' => $pelanggan->email,
                    'phone' => $pelanggan->no_hp ?? '081234567890',
                    'address' => $address,
                    'city' => $city,
                    'postal_code' => $postalCode,
                    'province' => $province,
                    'country_code' => 'IDN',
                ],
            ];
            Log::info('Midtrans params', ['params' => $params]);

            // Panggil Midtrans API
            $snapToken = Snap::getSnapToken($params);
            Log::info('Snap Token obtained', ['token' => $snapToken]);

            // Simpan snap_token
            $updateSnapResult = $kredit->update(['snap_token' => $snapToken]);
            if ($updateSnapResult) {
                Log::info('Snap Token saved to kredit successfully', ['kredit_id' => $kredit->id, 'snap_token' => $snapToken]);
            } else {
                Log::error('Failed to save Snap Token to kredit', ['kredit_id' => $kredit->id, 'snap_token' => $snapToken]);
                return response()->json(['error' => 'Gagal menyimpan snap_token ke kredit.'], 500);
            }

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Failed to get Snap Token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'pengajuanId' => $pengajuanId,
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update status pembayaran setelah sukses dari client-side
     */
    public function updatePaymentStatus(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            if (!$orderId) {
                Log::error('Missing order_id', ['request' => $request->all()]);
                return response()->json(['error' => 'Order ID diperlukan.'], 400);
            }

            $transactionStatus = $request->input('transaction_status');
            if (!$transactionStatus) {
                Log::error('Missing transaction_status', ['request' => $request->all()]);
                return response()->json(['error' => 'Status transaksi diperlukan.'], 400);
            }

            $paymentType = $request->input('payment_type', ''); // Default ke string kosong jika null
            $vaNumbers = $request->input('va_numbers', []);
            $selectedAddress = $request->input('selected_address', []); // Get selected address

            Log::info('Updating payment status from client', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'va_numbers' => $vaNumbers,
                'selected_address' => $selectedAddress,
                'request_all' => $request->all(),
            ]);

            // Store selected address in session if provided
            if (!empty($selectedAddress)) {
                session()->put('selected_address', $selectedAddress);
                Log::info('Selected address stored in session', ['selected_address' => $selectedAddress]);
            } else {
                Log::warning('No selected address provided', ['order_id' => $orderId]);
            }

            // Cari kredit berdasarkan order_id
            $kredit = Kredit::where('order_id', $orderId)->first();
            if (!$kredit) {
                Log::error('Kredit not found', ['order_id' => $orderId]);
                return response()->json(['error' => 'Kredit tidak ditemukan.'], 404);
            }

            $pengajuan = PengajuanKredit::find($kredit->pengajuan_kredit_id);
            if (!$pengajuan) {
                Log::error('Pengajuan not found', ['pengajuan_kredit_id' => $kredit->pengajuan_kredit_id]);
                return response()->json(['error' => 'Pengajuan tidak ditemukan.'], 404);
            }

            // Normalisasi payment_type
            $paymentMethodName = $this->normalizePaymentType($paymentType);
            $tempatBayar = null;
            $vaNumber = null;
            if (!empty($vaNumbers) && is_array($vaNumbers)) {
                $vaData = $vaNumbers[0] ?? null;
                if (is_array($vaData)) {
                    $tempatBayar = isset($vaData['bank']) ? "Bank " . strtoupper($vaData['bank']) : null;
                    $vaNumber = $vaData['va_number'] ?? null;
                } else {
                    Log::warning('va_numbers format invalid', ['va_numbers' => $vaNumbers]);
                }
            }

            // Cari atau buat metode pembayaran
            $metodePembayaran = null;
            if ($tempatBayar) {
                $metodePembayaran = MetodePembayaran::where('tempat_bayar', $tempatBayar)->first();
                if (!$metodePembayaran) {
                    $metodePembayaran = MetodePembayaran::create([
                        'metode_pembayaran' => $paymentMethodName,
                        'tempat_bayar' => $tempatBayar,
                        'no_rekening' => $vaNumber,
                        'logo' => null,
                    ]);
                    Log::info('New payment method created', ['metode_pembayaran' => $paymentMethodName, 'tempat_bayar' => $tempatBayar, 'id' => $metodePembayaran->id]);
                } else {
                    Log::info('Payment method found by tempat_bayar', ['metode_pembayaran' => $paymentMethodName, 'tempat_bayar' => $tempatBayar, 'id' => $metodePembayaran->id]);
                }
            } else {
                Log::warning('No tempat_bayar defined, skipping MetodePembayaran creation', ['payment_type' => $paymentType]);
            }

            // Update metode_pembayaran_id di kredit jika ada
            if ($metodePembayaran) {
                $kredit->metode_pembayaran_id = $metodePembayaran->id;
            }

            // Update status berdasarkan transaction_status dari Midtrans
            $isSuccess = false;
            if (in_array($transactionStatus, ['success', 'settlement', 'capture'])) {
                $isSuccess = true;
                $kredit->payment_status = 'paid';
                $kredit->status_kredit = 'Dicicil';
                $kredit->save();
                Log::info('Payment status updated to paid', ['kredit_id' => $kredit->id]);

                $pengajuan->status_pengajuan = 'Diterima';
                $pengajuan->save();
                Log::info('Pengajuan status updated to Diterima', ['pengajuan_id' => $pengajuan->id]);
            } elseif ($transactionStatus === 'pending') {
                $kredit->payment_status = 'pending';
                $kredit->save();
                Log::info('Payment status updated to pending', ['kredit_id' => $kredit->id]);
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $kredit->payment_status = 'failed';
                $kredit->status_kredit = 'Dicicil';
                $kredit->save();
                Log::info('Payment status updated to failed', ['kredit_id' => $kredit->id]);

                $pengajuan->status_pengajuan = 'Menunggu Pembayaran';
                $pengajuan->save();
                Log::info('Pengajuan status updated to Menunggu Pembayaran', ['pengajuan_id' => $pengajuan->id]);
            } else {
                Log::warning('Invalid transaction status from client', ['order_id' => $orderId, 'status' => $transactionStatus]);
                return response()->json(['error' => 'Status transaksi tidak valid.'], 400);
            }

            if ($isSuccess) {
                return response()->json(['status' => 'success']);
            }
            return response()->json(['status' => 'pending']);
        } catch (\Exception $e) {
            Log::error('Failed to update payment status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $request->input('order_id'),
                'request_all' => $request->all(),
            ]);
            return response()->json(['error' => 'Terjadi kesalahan di server: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Normalisasi payment_type dari Midtrans menjadi nama yang lebih readable
     */
    private function normalizePaymentType($paymentType)
    {
        $paymentMethods = [
            'bank_transfer' => 'Bank Transfer',
            'qris' => 'QRIS',
            'credit_card' => 'Credit Card',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'mandiri_bill' => 'Mandiri Bill',
            'permata_va' => 'Permata Virtual Account',
            'alfamart' => 'Alfamart',
            'indomaret' => 'Indomaret',
        ];

        return $paymentMethods[$paymentType] ?? ucfirst(str_replace('_', ' ', $paymentType));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $pelangganId = Auth::guard('pelanggan')->id();

        // Add orderBy('created_at', 'desc') and paginate()
        $pengajuan = PengajuanKredit::with(['Motor', 'JenisCicilan', 'Kredit'])
            ->where('pelanggan_id', $pelangganId)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 10 items per page

        $pengiriman = Pengirimans::whereHas('Kredit.PengajuanKredit', function ($query) use ($pelangganId) {
            $query->where('pelanggan_id', $pelangganId);
        })->with(['Kredit.PengajuanKredit.Motor', 'Kredit.PengajuanKredit.Pelanggan'])->get();

        return view('c-kredit.index', compact('pengajuan', 'pengiriman'), [
            'title' => 'Pengajuan Saya'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($pengajuanId)
    {
        $pengajuan = PengajuanKredit::with(['JenisCicilan', 'Motor'])
            ->where('pelanggan_id', Auth::guard('pelanggan')->id())
            ->where('id', $pengajuanId)
            ->firstOrFail();
        $pelanggan = Auth::guard('pelanggan')->user();
        return view('c-kredit.create', compact('pengajuan', 'pelanggan'))
            ->with('title', 'Pembayaran Kredit');
    }

    /**
     * Update status pengajuan (cancel or reject)
     */
    public function updateStatus(Request $request, $id, $action)
    {
        try {
            $pengajuan = PengajuanKredit::findOrFail($id);
            $motor = Motor::findOrFail($pengajuan->motor_id);

            if ($action === 'cancel') {
                $pelanggan = Auth::guard('pelanggan')->user();
                if ($pengajuan->pelanggan_id !== $pelanggan->id) {
                    return redirect()->route('pengajuan')->with('error', 'Anda tidak memiliki akses untuk membatalkan pengajuan ini.');
                }
                if (!in_array($pengajuan->status_pengajuan, ['Menunggu Konfirmasi', 'Diproses'])) {
                    return redirect()->route('pengajuan')->with('error', 'Pengajuan tidak dapat dibatalkan karena statusnya sudah ' . $pengajuan->status_pengajuan . '.');
                }
                $request->validate([
                    'keterangan_status_pengajuan' => 'required|min:10',
                ]);
                $newStatus = 'Dibatalkan Pembeli';
                $successMessage = 'Pengajuan berhasil dibatalkan dengan alasan: ' . $request->keterangan_status_pengajuan;
            } elseif ($action === 'reject') {
                if (!Auth::guard('admin')->check()) {
                    return redirect()->route('pengajuan')->with('error', 'Anda tidak memiliki akses untuk menolak pengajuan ini.');
                }
                if (!in_array($pengajuan->status_pengajuan, ['Menunggu Konfirmasi', 'Diproses'])) {
                    return redirect()->route('pengajuan')->with('error', 'Pengajuan tidak dapat ditolak karena statusnya sudah ' . $pengajuan->status_pengajuan . '.');
                }
                $keterangan = $request->input('keterangan_status_pengajuan', 'Ditolak oleh admin');
                $newStatus = 'Dibatalkan Penjual';
                $successMessage = 'Pengajuan berhasil ditolak dan stok telah dikembalian.';
            } else {
                return redirect()->route('pengajuan')->with('error', 'Aksi tidak valid.');
            }

            DB::transaction(function () use ($pengajuan, $motor, $newStatus, $request, $action) {
                if (!$pengajuan->is_stock_returned && in_array($newStatus, ['Dibatalkan Pembeli', 'Dibatalkan Penjual'])) {
                    $motor->stok += 1;
                    $motor->save();
                    $pengajuan->is_stock_returned = true;
                }

                $pengajuan->status_pengajuan = $newStatus;
                $pengajuan->keterangan_status_pengajuan = $action === 'cancel' ?
                    $request->keterangan_status_pengajuan :
                    ($request->input('keterangan_status_pengajuan', $newStatus));
                $pengajuan->save();

                if ($action === 'cancel') {
                    event(new KreditDibatalkan($pengajuan));
                }
            });

            return redirect()->route('pengajuan')->with('success', $successMessage);
        } catch (\Exception $e) {
            return redirect()->route('pengajuan')->with('error', 'Gagal memproses aksi: ' . $e->getMessage());
        }
    }
}