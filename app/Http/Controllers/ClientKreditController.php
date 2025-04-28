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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClientKreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelangganId = Auth::guard('pelanggan')->id();
        
        // Ambil data pengajuan kredit dengan relasi
        $pengajuan = PengajuanKredit::with(['motor', 'jenisCicilan', 'asuransi', 'kredit'])
            ->where('pelanggan_id', $pelangganId)
            ->get();
        
        // Ambil data pengiriman terkait
        $pengiriman = Pengirimans::whereHas('kredit.PengajuanKredit', function ($query) use ($pelangganId) {
            $query->where('pelanggan_id', $pelangganId);
        })->with(['kredit.PengajuanKredit.motor'], ['kredit.PengajuanKredit.Pelanggan'])->get();
    
        return view('c-kredit.index', compact('pengajuan', 'pengiriman'), [
            'title' => 'Pengajuan Saya'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelanggan = Auth::guard('pelanggan')->id();
        $pengajuan = PengajuanKredit::where('pelanggan_id', $pelanggan)
            ->firstOrFail();
        $metodePembayarans = MetodePembayaran::all(); // Ambil semua metode pembayaran

        // Pastikan status pengajuan memungkinkan pembayaran
        if ($pengajuan->status_pengajuan !== 'Diproses') {
            return redirect()->route('pengajuan')->with('error', 'Pengajuan ini tidak dapat dibayar karena statusnya bukan "Diproses".');
        }

        return view('c-kredit.create', compact('pengajuan', 'metodePembayarans'))
        ->with('title', 'Pembayaran Kredit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Starting store method', $request->all());
    
            // Validasi input
            $request->validate([
                'pengajuan_kredit_id' => 'required|exists:pengajuan_kredits,id',
                'metode_pembayaran_id' => 'required|exists:metode_pembayarans,id',
                'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'status_kredit' => 'required|string',
                'keterangan_status_kredit' => 'nullable|string',
            ]);
    
            Log::info('Validation passed');
    
            // Ambil data pengajuan
            $pengajuan = PengajuanKredit::with('jenisCicilan')->findOrFail($request->pengajuan_kredit_id);
            Log::info('Pengajuan fetched', $pengajuan->toArray());
    
            // Hitung tanggal mulai dan selesai kredit
            $tglMulai = Carbon::now();
            $tglSelesai = $tglMulai->copy()->addMonths($pengajuan->jenisCicilan->lama_cicilan ?? 0);
    
            // Upload file bukti pembayaran
            $path = null;
            if ($request->hasFile('bukti_bayar')) {
                $file = $request->file('bukti_bayar');
                $path = $file->store('bukti_bayar', 'public');
                Log::info('File uploaded: ' . $path);
            } else {
                Log::error('No file uploaded');
            }
    
            // Simpan data ke tabel kredit
            Kredit::create([
                'pengajuan_kredit_id' => $pengajuan->id,
                'metode_pembayaran_id' => $request->metode_pembayaran_id,
                'tgl_mulai_kredit' => $tglMulai,
                'tgl_selesai_kredit' => $tglSelesai,
                'status_kredit' => $request->status_kredit,
                'sisa_kredit' => $pengajuan->harga_kredit - $pengajuan->dp, // Hitung langsung
                'url_bukti_bayar' => $path,
                'keterangan_status_kredit' => $request->keterangan_status_kredit,
            ]);
    
            Log::info('Kredit saved');
    
            // Ubah status pengajuan
            $pengajuan->status_pengajuan = 'Diterima';
            $pengajuan->save();
    
            return redirect()->route('pengajuan')->with('success', 'Pembayaran berhasil dilakukan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error', 'Pembayaran gagal: Silakan periksa input Anda.');
        } catch (\Exception $e) {
            Log::error('Error processing payment', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request, $id, $action)
    {
        try {
            $pengajuan = PengajuanKredit::findOrFail($id);
            $motor = Motor::findOrFail($pengajuan->motor_id);

            // Validasi berdasarkan aksi
            if ($action === 'cancel') {
                $pelanggan = Auth::guard('pelanggan')->user();
                if ($pengajuan->pelanggan_id !== $pelanggan->id) {
                    return redirect()->route('pengajuan')->with('error', 'Anda tidak memiliki akses untuk membatalkan pengajuan ini.');
                }
                if (!in_array($pengajuan->status_pengajuan, ['Menunggu Konfirmasi', 'Diproses'])) {
                    return redirect()->route('pengajuan')->with('error', 'Pengajuan tidak dapat dibatalkan karena statusnya sudah ' . $pengajuan->status_pengajuan . '.');
                }
                $newStatus = 'Dibatalkan Pembeli';
                $successMessage = 'Pengajuan berhasil dibatalkan.';
            } elseif ($action === 'reject') {
                if (!Auth::guard('admin')->check()) {
                    return redirect()->route('pengajuan')->with('error', 'Anda tidak memiliki akses untuk menolak pengajuan ini.');
                }
                if (!in_array($pengajuan->status_pengajuan, ['Menunggu Konfirmasi', 'Diproses'])) {
                    return redirect()->route('pengajuan')->with('error', 'Pengajuan tidak dapat ditolak karena statusnya sudah ' . $pengajuan->status_pengajuan . '.');
                }
                $newStatus = 'Ditolak';
                $successMessage = 'Pengajuan berhasil ditolak dan stok telah dikembalikan.';
            } else {
                return redirect()->route('pengajuan')->with('error', 'Aksi tidak valid.');
            }

            DB::transaction(function () use ($pengajuan, $motor, $newStatus) {
                // Kembalikan stok hanya jika belum dikembalikan
                if (!$pengajuan->is_stock_returned && in_array($newStatus, ['Dibatalkan Pembeli', 'Ditolak'])) {
                    $motor->stok += 1;
                    $motor->save();
                    $pengajuan->is_stock_returned = true;
                }

                // Update status pengajuan
                $pengajuan->status_pengajuan = $newStatus;
                $pengajuan->keterangan_status_pengajuan = $newStatus;
                $pengajuan->save();
            });

            return redirect()->route('pengajuan')->with('success', $successMessage);
        } catch (\Exception $e) {
            return redirect()->route('pengajuan')->with('error', 'Gagal memproses aksi: ' . $e->getMessage());
        }
    }
}
