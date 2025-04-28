<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\PengajuanKredit;
use App\Models\Pengirimans;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ClientPengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view('c-pengiriman.index', ['title' => 'Pengiriman']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($pengajuanId)
    {
        try {
            Log::info('Mengakses form pengiriman', [
                'pengajuan_id' => $pengajuanId,
                'pelanggan_id' => Auth::guard('pelanggan')->id()
            ]);

            if (!Auth::guard('pelanggan')->check()) {
                Log::error('Pelanggan tidak terautentikasi');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            $pelanggan = Auth::guard('pelanggan')->user();
            if ($pelanggan->hasAlamat()) {
                return redirect()->route('pengiriman.store', $pengajuanId);
            }

            $pengajuan = PengajuanKredit::with('motor')
                ->where('pelanggan_id', Auth::guard('pelanggan')->id())
                ->where('status_pengajuan', 'Diterima')
                ->findOrFail($pengajuanId);

            $kredit = Kredit::where('pengajuan_kredit_id', $pengajuanId)
                ->firstOrFail();

            Log::info('Menampilkan form pengiriman', [
                'pengajuan_id' => $pengajuanId,
                'kredit_id' => $kredit->id,
                'pelanggan_id' => Auth::guard('pelanggan')->id()
            ]);

            return view('c-pengiriman.create', compact('kredit', 'pengajuan'), ['title' => 'Pengiriman']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Data tidak ditemukan', [
                'message' => $e->getMessage(),
                'pengajuan_id' => $pengajuanId
            ]);
            return redirect()->route('pengajuan')
                ->with('error', 'Pengajuan atau kredit tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Gagal memuat form pengiriman', [
                'message' => $e->getMessage(),
                'pengajuan_id' => $pengajuanId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('pengajuan')
                ->with('error', 'Gagal memuat form pengiriman: ' . $e->getMessage());
        }
    }

    public function store(Request $request, $pengajuanId)
    {
        try {
            Log::info('Menyimpan pengiriman', [
                'pengajuan_id' => $pengajuanId,
                'pelanggan_id' => Auth::guard('pelanggan')->id()
            ]);

            if (!Auth::guard('pelanggan')->check()) {
                Log::error('Pelanggan tidak terautentikasi');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            $pelanggan = Auth::guard('pelanggan')->user();
            $pengajuan = PengajuanKredit::where('pelanggan_id', $pelanggan->id)
                ->where('status_pengajuan', 'Diterima')
                ->findOrFail($pengajuanId);

            $kredit = Kredit::where('pengajuan_kredit_id', $pengajuanId)
                ->firstOrFail();

            if (Pengirimans::where('kredit_id', $kredit->id)->exists()) {
                return redirect()->route('pengajuan')
                    ->with('error', 'Pengiriman untuk kredit ini sudah dijadwalkan.');
            }

            // Validasi input
            $rules = [
                'tanggal_pengiriman' => 'required|date|after:today',
            ];

            if (!$pelanggan->hasAlamat()) {
                $rules['alamat1'] = 'required|string|max:255';
                $rules['kota1'] = 'required|string|max:100';
                $rules['provinsi1'] = 'required|string|max:100';
                $rules['kode_pos1'] = 'required|string|max:10';
            }

            $validated = $request->validate($rules);

            // Jika alamat diisi (tidak ada alamat sebelumnya), simpan ke tabel pelanggans
            if (isset($validated['alamat1'])) {
                $pelanggan->update([
                    'alamat1' => $validated['alamat1'],
                    'kota1' => $validated['kota1'],
                    'provinsi1' => $validated['provinsi1'],
                    'kode_pos1' => $validated['kode_pos1'],
                ]);
                Log::info('Alamat pelanggan diperbarui', [
                    'pelanggan_id' => $pelanggan->id,
                    'alamat1' => $validated['alamat1'],
                    'kota1' => $validated['kota1'],
                    'provinsi1' => $validated['provinsi1'],
                    'kode_pos1' => $validated['kode_pos1'],
                ]);
            }

            // Simpan pengiriman
            $pengiriman = Pengirimans::create([
                'kredit_id' => $kredit->id,
                'tgl_kirim' => $validated['tanggal_pengiriman'],
                'status_kirim' => 'Menunggu Konfirmasi',
                'keterangan' => $pelanggan->alamat_utama, // Simpan alamat utama di keterangan
            ]);

            Log::info('Pengiriman berhasil dijadwalkan', [
                'pengiriman_id' => $pengiriman->id,
                'kredit_id' => $kredit->id,
                'pengajuan_id' => $pengajuanId
            ]);

            return redirect()->route('pengajuan')
                ->with('success', 'Jadwal pengiriman berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi gagal', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Data tidak ditemukan', ['message' => $e->getMessage()]);
            return redirect()->route('pengajuan')
                ->with('error', 'Pengajuan atau kredit tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengiriman', ['message' => $e->getMessage()]);
            return redirect()->route('pengajuan')
                ->with('error', 'Gagal menyimpan jadwal pengiriman: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
}
