<?php

namespace App\Http\Controllers;

use App\Models\PengajuanKredit;
use App\Models\Kredit;
use App\Models\Angsuran;
use App\Models\MetodePembayaran;
use App\Models\Pengirimans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/* MIDTRANS IMPORTS (Commented for now)
use Midtrans\Config;
use Midtrans\Snap;
use App\Services\ReceiptService;
*/

class ClientAngsuranController extends Controller
{
    /* MIDTRANS CONSTRUCTOR (Commented)
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }
    */

    /* MIDTRANS GET SNAP TOKEN (Commented)
    public function getSnapToken(Request $request, $pengajuanId)
    {
        try {
            $pengajuan = PengajuanKredit::findOrFail($pengajuanId);
            $kredit = Kredit::where('pengajuan_kredit_id', $pengajuan->id)->firstOrFail();
            $pelanggan = Auth::guard('pelanggan')->user();

            if ($pengajuan->pelanggan_id !== $pelanggan->id) {
                return response()->json(['error' => 'Akses ditolak.'], 403);
            }

            $bulan = $request->input('bulan');
            $orderId = 'ANG-' . $kredit->id . '-' . str_replace('-', '', $bulan) . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $pengajuan->cicilan_perbulan,
                ],
                'customer_details' => [
                    'first_name' => $pelanggan->nama_pelanggan,
                    'email' => $pelanggan->email,
                    'phone' => $pelanggan->no_hp,
                ],
                'item_details' => [
                    [
                        'id' => 'ANG-' . $bulan,
                        'price' => (int) $pengajuan->cicilan_perbulan,
                        'quantity' => 1,
                        'name' => 'Angsuran ' . $pengajuan->Motor->nama_motor . ' - ' . $bulan,
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            Angsuran::create([
                'kredit_id' => $kredit->id,
                'tgl_bayar' => $bulan,
                'angsuran_ke' => $kredit->angsuran()->count() + 1,
                'total_bayar' => $pengajuan->cicilan_perbulan,
                'order_id' => $orderId,
                'keterangan' => 'Pending Midtrans - ' . $bulan,
            ]);

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    */

    /* MIDTRANS CLIENT UPDATE (Commented)
    public function updatePaymentStatus(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            $status = $request->input('transaction_status');
            
            $angsuran = Angsuran::where('order_id', $orderId)->first();
            if (!$angsuran) return response()->json(['error' => 'Data tidak ditemukan'], 404);

            if (in_array($status, ['capture', 'settlement', 'success'])) {
                $angsuran->keterangan = 'Lunas (Midtrans)';
                $angsuran->transaction_id = $request->input('transaction_id');
                $angsuran->bukti_angsuran = ReceiptService::generateAngsuranReceipt($angsuran);
                $angsuran->save();

                $kredit = $angsuran->Kredit->fresh();
                if ($kredit->sisa_kredit <= 0) {
                    $kredit->status_kredit = 'Lunas';
                    $kredit->save();
                }
                return response()->json(['status' => 'success']);
            }
            return response()->json(['status' => 'pending']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    */

    public function index() { }

    public function create() { }

    public function store(Request $request, $pengajuanId)
    {
        try {
            $request->merge([
                'jumlah_bayar' => str_replace(['.', ','], '', $request->jumlah_bayar)
            ]);

            $validated = $request->validate([
                'bulan' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($pengajuanId) {
                        $pengajuan = PengajuanKredit::findOrFail($pengajuanId);
                        $kredit = Kredit::where('pengajuan_kredit_id', $pengajuan->id)->firstOrFail();
                        $bulanFormat = Carbon::parse($value)->format('Y-m');
                        $angsuranTerbayar = Angsuran::where('kredit_id', $kredit->id)
                            ->pluck('tgl_bayar')
                            ->map(function ($tgl) {
                                return $tgl ? Carbon::parse($tgl)->format('Y-m') : null;
                            })->filter()->toArray();
                        if (in_array($bulanFormat, $angsuranTerbayar)) {
                            $fail('Bulan ini sudah dibayar.');
                        }
                    },
                ],
                'jumlah_bayar' => 'required|numeric|min:0',
                'bukti_angsuran' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $pengajuan = PengajuanKredit::findOrFail($pengajuanId);
            
            if ($pengajuan->pelanggan_id !== Auth::guard('pelanggan')->id()) {
                return redirect()->route('pengajuan')->with('error', 'Akses ditolak.');
            }

            $kredit = Kredit::where('pengajuan_kredit_id', $pengajuan->id)->firstOrFail();

            if ($request->jumlah_bayar < $pengajuan->cicilan_perbulan) {
                 return redirect()->back()->with('error', 'Jumlah bayar minimal Rp ' . number_format($pengajuan->cicilan_perbulan, 0, ',', '.'))->withInput();
            }

            $angsuranTerakhir = Angsuran::where('kredit_id', $kredit->id)->latest()->first();
            $angsuranKe = $angsuranTerakhir ? $angsuranTerakhir->angsuran_ke + 1 : 1;

            $data = [
                'kredit_id' => $kredit->id,
                'tgl_bayar' => $validated['bulan'], // Gunakan bulan dari input
                'angsuran_ke' => $angsuranKe,
                'total_bayar' => $validated['jumlah_bayar'],
                'bukti_angsuran' => $request->file('bukti_angsuran')->store('bukti_angsuran', 'public'),
                'keterangan' => 'Pembayaran angsuran untuk bulan ' . Carbon::parse($validated['bulan'])->translatedFormat('F Y'),
            ];            

            $angsuran = Angsuran::create($data);
            
            $kredit = $kredit->fresh();

            if ($kredit->sisa_kredit <= 0 && $kredit->status_kredit !== 'Lunas') {
                $kredit->update([
                    'status_kredit' => 'Lunas',
                    'updated_at' => now(),
                ]);

                return redirect()->route('cicilan', $pengajuan->id)
                    ->with('success', 'Pembayaran angsuran berhasil disimpan. Selamat, kredit Anda telah lunas!');
            }

            return redirect()->route('cicilan', $pengajuan->id)
                ->with('success', 'Pembayaran angsuran berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('cicilan', $pengajuanId)
                ->with('error', 'Gagal menyimpan angsuran: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $pelanggan = Auth::guard('pelanggan')->user();

            $kredit = Kredit::with(['angsuran', 'PengajuanKredit.motor', 'PengajuanKredit.jenisCicilan'])
                ->where('pengajuan_kredit_id', $id)
                ->whereHas('PengajuanKredit', function ($query) use ($pelanggan) {
                    $query->where('pelanggan_id', $pelanggan->id);
                })
                ->first();
    
            if (!$kredit) {
                return redirect()->route('pengajuan')
                    ->with('error', 'Kredit belum tersedia untuk pengajuan ini. Silakan hubungi administrator.');
            }
    
            return view('c-angsuran.index', [
                'kredit' => $kredit,
                'pengajuan' => $kredit->PengajuanKredit,
                'angsuranList' => $kredit->angsuran,
                'title' => 'Angsuran Kredit',
                'pelanggan' => $pelanggan,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('pengajuan')
                ->with('error', 'Gagal memuat detail angsuran: ' . $e->getMessage());
        }
    }
}
