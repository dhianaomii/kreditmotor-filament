<?php

namespace App\Http\Controllers;

use App\Models\PengajuanKredit;
// use App\Models\Kredit;
use App\Models\Kredit;
use App\Models\Angsuran;
use App\Models\MetodePembayaran;
use App\Models\Pengirimans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClientAngsuranController extends Controller
{
    public function index()
    {
        // Kosong sesuai kode asli
    }

    public function create()
    {
        // Kosong sesuai kode asli
    }

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
            $kredit = Kredit::where('pengajuan_kredit_id', $pengajuan->id)->firstOrFail();
            Log::info('Data ditemukan', [
                'pengajuan_id' => $pengajuan->id,
                'kredit_id' => $kredit->id
            ]);

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
            
            $totalAngsuran = Angsuran::where('kredit_id', $kredit->id)->count();
            $lamaCicilan = $pengajuan->jenisCicilan->lama_cicilan;

            if ($totalAngsuran >= $lamaCicilan && $kredit->status_kredit !== 'Lunas') {
                $kredit->update([
                    'status_kredit' => 'Lunas',
                    'updated_at' => now(),
                ]);

                return redirect()->route('cicilan', $pengajuan->id)
                    ->with('success', 'Pembayaran angsuran berhasil disimpan. Selamat, kredit Anda telah lunas!');
            }

            return redirect()->route('cicilan', $pengajuan->id)
                ->with('success', 'Pembayaran angsuran berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log::error('Validasi gagal', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Log::error('Data tidak ditemukan', ['message' => $e->getMessage()]);
            return redirect()->route('cicilan', $pengajuanId)
                ->with('error', 'Pengajuan atau kredit tidak ditemukan.');
        } catch (\Exception $e) {
            // Log::error('Gagal menyimpan angsuran', ['message' => $e->getMessage()]);
            return redirect()->route('cicilan', $pengajuanId)
                ->with('error', 'Gagal menyimpan angsuran: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $pelanggan = Auth::guard('pelanggan')->user();

            Log::info('Mencari kredit untuk pengajuan_id', ['id' => $id]);
    
            $kredit = Kredit::with(['angsuran', 'PengajuanKredit.motor', 'PengajuanKredit.jenisCicilan'])
                ->where('pengajuan_kredit_id', $id)
                ->first();
    
            if (!$kredit) {
                Log::error('Kredit tidak ditemukan', ['pengajuan_kredit_id' => $id]);
                return redirect()->route('pengajuan')
                    ->with('error', 'Kredit belum tersedia untuk pengajuan ini. Silakan hubungi administrator.');
            }
    
            Log::info('Menampilkan detail angsuran', [
                'pengajuan_id' => $id,
                'kredit_id' => $kredit->id,
                'angsuran_count' => $kredit->angsuran->count()
            ]);
    
            return view('c-angsuran.index', [
                'kredit' => $kredit,
                'pengajuan' => $kredit->PengajuanKredit,
                'angsuranList' => $kredit->angsuran,
                'title' => 'Angsuran Kredit',
                'pelanggan' => $pelanggan,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memuat detail angsuran', ['message' => $e->getMessage()]);
            return redirect()->route('pengajuan')
                ->with('error', 'Gagal memuat detail angsuran: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}