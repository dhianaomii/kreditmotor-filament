<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Kredit;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\PengajuanKredit;
use App\Models\Pengirimans;
use Barryvdh\DomPDF\Facade\Pdf;

class ArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';
        return view('arsip.index', [
            'title' => $title . ' - User',
            'menu' => 'Pelanggan',
            'pelanggan' => Pelanggan::all(),
            'kredit' => Kredit::all(),
            'pengajuan' => PengajuanKredit::all(),
            'angsuran' => Angsuran::all(),
            'pengiriman' => Pengirimans::all()
        ]);
    }

    public function exportPelangganPdf()
    {
        $pelanggan = Pelanggan::all();
        $pdf = Pdf::loadView('arsip.pelanggan', compact('pelanggan'));
        return $pdf->download('Data_Pelanggan.pdf');
    }

    public function exportKreditPdf()
    {
        $kredit = Kredit::with(['PengajuanKredit.Pelanggan', 'MetodePembayaran'])->get();
        $pdf = Pdf::loadView('arsip.kredit', compact('kredit'));
        return $pdf->download('Data_Kredit.pdf');
    }

    public function exportPengajuanPdf()
    {
        $pengajuan = PengajuanKredit::with(['Pelanggan', 'Motor', 'JenisCicilan'])->get();
        $pdf = Pdf::loadView('arsip.pengajuan', compact('pengajuan'));
        return $pdf->download('Data_Pengajuan_Kredit.pdf');
    }

    public function exportAngsuranPdf()
    {
        $angsuran = Angsuran::with(['Kredit.PengajuanKredit.Pelanggan'])->get();
        $pdf = Pdf::loadView('arsip.angsuran', compact('angsuran'));
        return $pdf->download('Data_Angsuran.pdf');
    }

    public function exportPengirimanPdf()
    {
        $pengiriman = Pengirimans::with(['Kredit.PengajuanKredit.Pelanggan'])->get();
        $pdf = Pdf::loadView('arsip.pengiriman', compact('pengiriman'));
        return $pdf->download('Data_Pengiriman.pdf');
    }

    // public function exportPelanggan(){
    //     return view('arsip.index');

    //     $plggn = Pelanggan::all();
    //     $pdf = Pdf::loadView('arsip.index', $plggn);
    //     return $pdf->download('invoice.pdf');
    // }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
