<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\Pelanggan;
use App\Models\PengajuanKredit;
use App\Models\Pengirimans;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    // public function create($pengajuanId)
    // {
    //     try {
    //         Log::info('Mengakses form pengiriman', [
    //             'pengajuan_id' => $pengajuanId,
    //             'pelanggan_id' => Auth::guard('pelanggan')->id()
    //         ]);

    //         if (!Auth::guard('pelanggan')->check()) {
    //             Log::error('Pelanggan tidak terautentikasi');
    //             return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
    //         }

    //         $pelanggan = Auth::guard('pelanggan')->user();
    //         if ($pelanggan->hasAlamat()) {
    //             return redirect()->route('kirim.store', $pengajuanId);
    //         }

    //         $pengajuan = PengajuanKredit::with('motor')
    //             ->where('pelanggan_id', Auth::guard('pelanggan')->id())
    //             ->where('status_pengajuan', 'Diterima')
    //             ->findOrFail($pengajuanId);

    //         $kredit = Kredit::where('pengajuan_kredit_id', $pengajuanId)
    //             ->firstOrFail();

    //         Log::info('Menampilkan form pengiriman', [
    //             'pengajuan_id' => $pengajuanId,
    //             'kredit_id' => $kredit->id,
    //             'pelanggan_id' => Auth::guard('pelanggan')->id()
    //         ]);

    //         return view('c-pengiriman.create', compact('kredit', 'pengajuan'), ['title' => 'Pengiriman']);
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         Log::error('Data tidak ditemukan', [
    //             'message' => $e->getMessage(),
    //             'pengajuan_id' => $pengajuanId
    //         ]);
    //         return redirect()->route('pengajuan')
    //             ->with('error', 'Pengajuan atau kredit tidak ditemukan');
    //     } catch (\Exception $e) {
    //         Log::error('Gagal memuat form pengiriman', [
    //             'message' => $e->getMessage(),
    //             'pengajuan_id' => $pengajuanId,
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return redirect()->route('pengajuan')
    //             ->with('error', 'Gagal memuat form pengiriman: ' . $e->getMessage());
    //     }
    // }

    public function store(Request $request, $pengajuanKreditId)
    {
        try {
            if (!Auth::guard('pelanggan')->check()) {
                Log::error('Pelanggan tidak terautentikasi');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            $pelanggan = Auth::guard('pelanggan')->user();

            // Validasi input
            $request->validate([
                'kredit_id' => 'required|exists:kredit,id',
                'alamat_type' => 'required|in:alamat1,alamat2,alamat3,manual',
                'alamat_pengiriman' => 'required|string',
                'tanggal_pengiriman' => 'required|date|after:today',
                'no_telepon' => 'required|string|max:15',
                'catatan' => 'nullable|string',
                'status_kirim' => 'required|in:Sedang Dikirim,Tiba Di Tujuan',
            ]);

            // Periksa apakah kredit ada dan sudah lunas
            $kredit = Kredit::where('id', $request->kredit_id)
                ->where('pengajuan_kredit_id', $pengajuanKreditId)
                ->firstOrFail();

            if ($kredit->status_kredit !== 'Lunas') {
                return redirect()->route('pengajuan')->with('error', 'Kredit belum lunas, tidak bisa mengajukan pengiriman');
            }

            // Periksa apakah sudah ada pengiriman untuk kredit ini
            if (Pengirimans::where('kredit_id', $kredit->id)->exists()) {
                return redirect()->route('pengajuan')->with('error', 'Pengiriman untuk kredit ini sudah diajukan');
            }

            // Generate nomor invoice
            $tanggal = now()->format('Ymd');
            $acak = strtoupper(Str::random(5));
            $noInvoice = "INV-$tanggal-$acak";

            // Gabungkan alamat, nomor telepon, dan catatan untuk disimpan di kolom keterangan
            $keterangan = "Alamat: " . $request->alamat_pengiriman;
            $keterangan .= "\nNomor Telepon: " . $request->no_telepon;
            if ($request->catatan) {
                $keterangan .= "\nCatatan: " . $request->catatan;
            }

            // Simpan pengiriman
            Pengirimans::create([
                'kredit_id' => $request->kredit_id,
                'no_invoice' => $noInvoice,
                'tgl_kirim' => $request->tanggal_pengiriman,
                'tgl_tiba' => null,
                'status_kirim' => $request->status_kirim,
                'nama_kurir' => null,
                'telpon_kurir' => null,
                'bukti_foto' => null,
                'keterangan' => $keterangan,
            ]);

            // Jika alamat manual, simpan ke profil pelanggan
            if ($request->alamat_type === 'manual') {
                $alamatParts = explode(', ', $request->alamat_pengiriman);
                if (count($alamatParts) === 3) {
                    if (!$pelanggan->alamat1) {
                        $pelanggan->alamat1 = $alamatParts[0];
                        $pelanggan->provinsi1 = $alamatParts[1];
                        $pelanggan->kode_pos1 = $alamatParts[2];
                    } elseif (!$pelanggan->alamat2) {
                        $pelanggan->alamat2 = $alamatParts[0];
                        $pelanggan->provinsi2 = $alamatParts[1];
                        $pelanggan->kode_pos2 = $alamatParts[2];
                    } elseif (!$pelanggan->alamat3) {
                        $pelanggan->alamat3 = $alamatParts[0];
                        $pelanggan->provinsi3 = $alamatParts[1];
                        $pelanggan->kode_pos3 = $alamatParts[2];
                    }
                    $pelanggan = Pelanggan::save();
                }
            }

            Log::info('Pengiriman berhasil diajukan', [
                'pengajuan_id' => $pengajuanKreditId,
                'kredit_id' => $request->kredit_id,
                'no_invoice' => $noInvoice
            ]);

            return redirect()->route('pengajuan')->with('success', 'Pengajuan pengiriman berhasil diajukan dengan nomor invoice: ' . $noInvoice);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengiriman', [
                'message' => $e->getMessage(),
                'pengajuan_id' => $pengajuanKreditId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('pengajuan')->with('error', 'Gagal menyimpan pengiriman: ' . $e->getMessage());
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
