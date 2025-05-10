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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Events\KreditDibatalkan;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

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
            $tglMulaiKredit = $request->query('tgl_mulai_kredit');
            if (!$tglMulaiKredit) {
                Log::error('Tanggal mulai kredit tidak diberikan');
                return response()->json(['error' => 'Tanggal mulai kredit diperlukan.'], 400);
            }

            $tglMulai = Carbon::parse($tglMulaiKredit);
            $tglSelesai = $tglMulai->copy()->addMonths($pengajuan->JenisCicilan->lama_cicilan ?? 0);

            // Gunakan pengajuan_kredit_id sebagai bagian dari order_id
            $orderId = 'DP-' . $pengajuan->id;
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
                    'metode_pembayaran_id' => 1, // Default metode pembayaran untuk Midtrans
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
                    'metode_pembayaran_id' => 1, // Default metode pembayaran untuk Midtrans
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
                ],
                'callbacks' => [
                    'finish' => route('pengajuan'), // Redirect ke halaman pengajuan setelah pembayaran
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
     * Menangani callback dari Midtrans
     */
    public function handleCallback(Request $request)
    {
        try {
            Log::info('Raw callback request', ['body' => $request->all()]);

            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            Log::info('Received Midtrans callback', [
                'transaction_status' => $transaction,
                'order_id' => $orderId,
                'fraud_status' => $fraud,
            ]);

            // Validasi signature key
            $localSignature = hash('sha512', $notif->order_id . $notif->status_code . $notif->gross_amount . config('services.midtrans.server_key'));
            if ($localSignature !== $notif->signature_key) {
                Log::error('Invalid signature key', ['order_id' => $orderId, 'expected' => $localSignature, 'received' => $notif->signature_key]);
                return response()->json(['status' => 'Invalid signature'], 403);
            }

            // Cari kredit berdasarkan order_id
            $kredit = Kredit::where('order_id', $orderId)->first();
            if (!$kredit) {
                Log::error('Kredit not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'Kredit not found'], 404);
            }

            // Update status pembayaran dan pengajuan
            $pengajuan = PengajuanKredit::find($kredit->pengajuan_kredit_id); 
            if (!$pengajuan) {
                Log::error('Pengajuan not found', ['pengajuan_kredit_id' => $kredit->pengajuan_kredit_id]);
                return response()->json(['status' => 'Pengajuan not found'], 404);
            }

            if ($transaction == 'capture' && $fraud == 'accept') {
                $kredit->payment_status = 'paid';
                $kredit->status_kredit = 'Dicicil';
                $kredit->save();
                Log::info('Payment status updated to paid', ['kredit_id' => $kredit->id]);

                $pengajuan->status_pengajuan = 'Diterima';
                $pengajuan->save();
                Log::info('Pengajuan status updated to Diterima', ['pengajuan_id' => $pengajuan->id]);
            } elseif ($transaction == 'settlement') {
                $kredit->payment_status = 'paid';
                $kredit->status_kredit = 'Dicicil';
                $kredit->save();
                Log::info('Payment status updated to paid', ['kredit_id' => $kredit->id]);

                $pengajuan->status_pengajuan = 'Diterima';
                $pengajuan->save();
                Log::info('Pengajuan status updated to Diterima', ['pengajuan_id' => $pengajuan->id]);
            } elseif ($transaction == 'pending') {
                $kredit->payment_status = 'pending';
                $kredit->save();
                Log::info('Payment status updated to pending', ['kredit_id' => $kredit->id]);
            } elseif ($transaction == 'deny' || $transaction == 'cancel') {
                $kredit->payment_status = 'failed';
                $kredit->status_kredit = 'Dicicil';
                $kredit->save();
                Log::info('Payment status updated to failed', ['kredit_id' => $kredit->id]);

                $pengajuan->status_pengajuan = 'Menunggu Pembayaran';
                $pengajuan->save();
                Log::info('Pengajuan status updated to Menunggu Pembayaran', ['pengajuan_id' => $pengajuan->id]);
            } elseif ($transaction == 'expire') {
                $kredit->payment_status = 'expired';
                $kredit->status_kredit = 'Dicicil';
                $kredit->save();
                Log::info('Payment status updated to expired', ['kredit_id' => $kredit->id]);

                $pengajuan->status_pengajuan = 'Menunggu Pembayaran';
                $pengajuan->save();
                Log::info('Pengajuan status updated to Menunggu Pembayaran', ['pengajuan_id' => $pengajuan->id]);
            }

            Log::info('Callback processed successfully', ['order_id' => $orderId, 'status' => $transaction]);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Callback handling failed', ['error' => $e->getMessage(), 'order_id' => $notif->order_id ?? 'N/A']);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
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

        $pengajuan = PengajuanKredit::with(['Motor', 'JenisCicilan', 'Kredit'])
            ->where('pelanggan_id', $pelangganId)
            ->get();

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

        return view('c-kredit.create', compact('pengajuan'))
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
                $successMessage = 'Pengajuan berhasil ditolak dan stok telah dikembalikan.';
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