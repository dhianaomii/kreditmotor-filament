<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisCicilan;
use App\Models\Asuransi;
use App\Models\Motor;
use App\Models\PengajuanKredit;
use App\Models\JenisMotor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pelanggan')->only(['create', 'store']);
    }

    public function index(Request $request)
    {
        $query = Motor::with('JenisMotor')->orderByRaw('CASE WHEN stok > 0 THEN 0 ELSE 1 END');

        if ($request->filled('jenis_motor')) {
            $query->where('jenis_motor_id', $request->jenis_motor);
        }

        $data = $query->get();
        $jenisMotors = JenisMotor::all();

        return view('product.index', compact('data', 'jenisMotors')
        ,['title' => 'Daftar Motor']);
    }

    public function create($id)
    {
        $motor = Motor::find($id);
        $jenisCicilan = JenisCicilan::all();
        $asuransi = Asuransi::all();
        // dd($motor);
        return view('product.create', compact('motor', 'jenisCicilan', 'asuransi') 
        ,['title' => 'Pengajuan Kredit']);
    }

    public function store(Request $request)
    {
        try {
            $activePengajuan = PengajuanKredit::where('pelanggan_id', $request->pelanggan_id)
                ->where('status_pengajuan', ['Menunggu Konfirmasi', 'Diproses', 'Diterima'])
                ->count();
            if ($activePengajuan >= 1) {
                return redirect()->back()->with('error', 'Anda telah mencapai batas maksimal pengajuan aktif.');
            }

            $validated = $request->validate([
                'pelanggan_id' => 'required|exists:pelanggans,id',
                'motor_id' => 'required|exists:motors,id',
                'harga_cash' => 'required|numeric|gt:0',
                'dp' => 'required|numeric|min:0|lte:harga_kredit',
                'jenis_cicilan_id' => 'required|exists:jenis_cicilans,id',
                'harga_kredit' => 'required|numeric|min:0',
                'asuransi_id' => 'required|exists:asuransis,id',
                'biaya_asuransi_perbulan' => 'required|numeric|min:0',
                'cicilan_perbulan' => 'required|numeric|min:0',
                'tgl_pengajuan_kredit' => 'required|date_format:Y-m-d',
                'status_pengajuan' => 'required|string',
                'keterangan_status_pengajuan' => 'nullable|string',
                'url_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'url_ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'url_npwp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'url_slip_gaji' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'url_foto' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $motor = Motor::findOrFail($request->motor_id);
            if (is_null($motor->harga_jual) || $motor->harga_jual <= 0) {
                return redirect()->back()->with('error', 'Harga motor tidak tersedia.');
            }
            if ((float)$request->harga_cash !== (float)$motor->harga_jual) {
                return redirect()->back()->with('error', 'Harga cash tidak valid.');
            }
            if ($motor->stok < 1) {
                return redirect()->back()->with('error', 'Stok motor tidak tersedia untuk pengajuan ini.');
            }

            $asuransi = Asuransi::findOrFail($request->asuransi_id);
            $cicilan = JenisCicilan::findOrFail($request->jenis_cicilan_id);
            $BiayaAsuransi = $asuransi->margin_asuransi * $motor->harga_jual / 100;
            if (abs($request->biaya_asuransi_perbulan - $BiayaAsuransi) > 1) {
                return redirect()->back()->with('error', 'Biaya asuransi tidak valid.');
            }
            $Dp = $motor->harga_jual * $cicilan->dp / 100;
            if (abs($request->dp - $Dp) > 1) {
                return redirect()->back()->with('error', 'DP tidak valid.');
            }
            $HargaKredit = $motor->harga_jual - $Dp + ($motor->harga_jual * $cicilan->margin_kredit / 100);
            if (abs($request->harga_kredit - $HargaKredit) > 1) {
                return redirect()->back()->with('error', 'Harga kredit tidak valid.');
            }
            $CicilanPerbulan = (($HargaKredit) / $cicilan->lama_cicilan) + $BiayaAsuransi;
            if (abs($request->cicilan_perbulan - $CicilanPerbulan) > 1) {
                return redirect()->back()->with('error', 'Cicilan per bulan tidak valid.');
            }
            

           // Gunakan transaksi untuk memastikan integritas data
        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $motor, $validated) {
            // Kurangi stok motor sebanyak 1
            $motor->stok -= 1;
            $motor->save();

            // Simpan pengajuan kredit
            PengajuanKredit::create([
                'tgl_pengajuan_kredit' => $request->tgl_pengajuan_kredit,
                'pelanggan_id' => $request->pelanggan_id,
                'motor_id' => $request->motor_id,
                'harga_cash' => $request->harga_cash,
                'dp' => $request->dp,
                'jenis_cicilan_id' => $request->jenis_cicilan_id,
                'harga_kredit' => $request->harga_kredit,
                'asuransi_id' => $request->asuransi_id,
                'biaya_asuransi_perbulan' => $request->biaya_asuransi_perbulan,
                'cicilan_perbulan' => $request->cicilan_perbulan,
                'url_kk' => $request->file('url_kk')->store('dokumen', 'public'),
                'url_ktp' => $request->file('url_ktp')->store('dokumen', 'public'),
                'url_npwp' => $request->hasFile('url_npwp') ? $request->file('url_npwp')->store('dokumen', 'public') : null,
                'url_slip_gaji' => $request->file('url_slip_gaji')->store('dokumen', 'public'),
                'url_foto' => $request->file('url_foto')->store('dokumen', 'public'),
                'status_pengajuan' => $request->status_pengajuan,
                'keterangan_status_pengajuan' => $request->keterangan_status_pengajuan,
            ]);
        });

            return redirect()->route('product.show', $motor->id)->with('success', 'Pengajuan kredit berhasil dikirim!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('error', 'Gagal mengajukan kredit: ' . implode(', ', \Illuminate\Support\Arr::flatten($e->errors())));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengajukan kredit: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $motor = Motor::with('jenisMotor')->findOrFail($id);
        $relatedMotors = Motor::where('id', '!=', $id)->limit(4)->get();

        return view('product.show', [
            'title' => 'Detail Motor',
            'motor' => $motor,
            'data' => $relatedMotors
        ]);
    }

}