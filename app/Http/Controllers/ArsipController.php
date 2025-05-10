<?php

namespace App\Http\Controllers;

use App\Models\Angsuran;
use App\Models\Kredit;
use App\Models\Pelanggan;
use App\Models\PengajuanKredit;
use App\Models\Pengirimans;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $title = auth()->check() ? ucfirst(auth()->user()->role) : 'Dashboard';
        $tab = $request->input('tab', 'pelanggan'); // Default to pelanggan tab
        $month = $request->input('month');
        $year = $request->input('year');

        // Initialize queries
        $pelanggan = Pelanggan::query();
        $kredit = Kredit::with(['PengajuanKredit.Pelanggan', 'MetodePembayaran']);
        $pengajuan = PengajuanKredit::with(['Pelanggan', 'Motor', 'JenisCicilan']);
        $angsuran = Angsuran::with(['Kredit.PengajuanKredit.Pelanggan']);
        $pengiriman = Pengirimans::with(['Kredit.PengajuanKredit.Pelanggan']);

        // Apply filters based on tab
        if ($month && $year) {
            if ($tab == 'pelanggan') {
                $pelanggan->whereMonth('created_at', $month)->whereYear('created_at', $year);
            } elseif ($tab == 'kredit') {
                $kredit->whereMonth('tgl_mulai_kredit', $month)->whereYear('tgl_mulai_kredit', $year);
            } elseif ($tab == 'pengajuan') {
                $pengajuan->whereMonth('tgl_pengajuan_kredit', $month)->whereYear('tgl_pengajuan_kredit', $year);
            } elseif ($tab == 'angsuran') {
                $angsuran->whereMonth('tgl_bayar', $month)->whereYear('tgl_bayar', $year);
            } elseif ($tab == 'pengiriman') {
                $pengiriman->whereMonth('tgl_kirim', $month)->whereYear('tgl_kirim', $year);
            }
        }

        return view('arsip.index', [
            'title' => $title . ' - User',
            'menu' => 'Pelanggan',
            'pelanggan' => $pelanggan->get(),
            'kredit' => $kredit->get(),
            'pengajuan' => $pengajuan->get(),
            'angsuran' => $angsuran->get(),
            'pengiriman' => $pengiriman->get(),
            'active_tab' => $tab
        ]);
    }

    public function exportPelangganPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $pelanggan = Pelanggan::query();
        if ($month && $year) {
            $pelanggan->whereMonth('created_at', $month)->whereYear('created_at', $year);
        }
        $pelanggan = $pelanggan->get();
        $pdf = Pdf::loadView('arsip.pelanggan', compact('pelanggan'));
        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : 'All';
        $filename = "Data_Pelanggan_{$monthName}_{$year}.pdf";
        return $pdf->download($filename);
    }

    public function exportKreditPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $kredit = Kredit::with(['PengajuanKredit.Pelanggan', 'MetodePembayaran']);
        if ($month && $year) {
            $kredit->whereMonth('tgl_mulai_kredit', $month)->whereYear('tgl_mulai_kredit', $year);
        }
        $kredit = $kredit->get();
        $pdf = Pdf::loadView('arsip.kredit', compact('kredit'));
        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : 'All';
        $filename = "Data_Kredit_{$monthName}_{$year}.pdf";
        return $pdf->download($filename);
    }

    public function exportPengajuanPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $pengajuan = PengajuanKredit::with(['Pelanggan', 'Motor', 'JenisCicilan']);
        if ($month && $year) {
            $pengajuan->whereMonth('tgl_pengajuan_kredit', $month)->whereYear('tgl_pengajuan_kredit', $year);
        }
        $pengajuan = $pengajuan->get();
        $pdf = Pdf::loadView('arsip.pengajuan', compact('pengajuan'));
        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : 'All';
        $filename = "Data_Pengajuan_Kredit_{$monthName}_{$year}.pdf";
        return $pdf->download($filename);
    }

    public function exportAngsuranPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $angsuran = Angsuran::with(['Kredit.PengajuanKredit.Pelanggan']);
        if ($month && $year) {
            $angsuran->whereMonth('tgl_bayar', $month)->whereYear('tgl_bayar', $year);
        }
        $angsuran = $angsuran->get();
        $pdf = Pdf::loadView('arsip.angsuran', compact('angsuran'));
        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : 'All';
        $filename = "Data_Angsuran_{$monthName}_{$year}.pdf";
        return $pdf->download($filename);
    }

    public function exportPengirimanPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $pengiriman = Pengirimans::with(['Kredit.PengajuanKredit.Pelanggan']);
        if ($month && $year) {
            $pengiriman->whereMonth('tgl_kirim', $month)->whereYear('tgl_kirim', $year);
        }
        $pengiriman = $pengiriman->get();
        $pdf = Pdf::loadView('arsip.pengiriman', compact('pengiriman'));
        $monthName = $month ? date('F', mktime(0, 0, 0, $month, 1)) : 'All';
        $filename = "Data_Pengiriman_{$monthName}_{$year}.pdf";
        return $pdf->download($filename);
    }
}