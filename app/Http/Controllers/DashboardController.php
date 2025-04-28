<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motor;
use App\Models\Pelanggan;
use App\Models\PengajuanKredit;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $title = auth()->check() 
        ? ucfirst(auth()->user()->role)
        : 'Dashboard';

        // Hitung total motor
        $totalMotor = Motor::count();
        $motorSebelumnya = Motor::where('created_at', '<', now()->subDays(30))->count();
        $perubahanMotor = $this->hitungPersentase($totalMotor, $motorSebelumnya);
        
        // Hitung total pelanggan
        $totalPelanggan = Pelanggan::count();
        $pelangganSebelumnya = Pelanggan::where('created_at', '<', now()->subDays(30))->count();
        $perubahanPelanggan = $this->hitungPersentase($totalPelanggan, $pelangganSebelumnya);
        
        // Hitung pengajuan kredit
        $pengajuanBaru = PengajuanKredit::whereDate('created_at', today())->count();
        $totalPengajuan = PengajuanKredit::count();
        $pengajuanSebelumnya = PengajuanKredit::where('created_at', '<', now()->subDays(30))->count();
        $perubahanPengajuan = $this->hitungPersentase($totalPengajuan, $pengajuanSebelumnya);
        
        // Hitung kredit disetujui
        $kreditDisetujui = PengajuanKredit::where('status_pengajuan', 'Diterima')->count();
        $totalNilaiKredit = PengajuanKredit::where('status_pengajuan', 'Diterima')->sum('harga_kredit');
        $totalDp = PengajuanKredit::sum('dp');
        $rataKredit = PengajuanKredit::where('status_pengajuan', 'Diterima')->avg('harga_kredit');
        $totalCicilanBulanan = PengajuanKredit::where('status_pengajuan', 'Diterima')->sum('cicilan_perbulan');
        $pengajuanMenunggu = PengajuanKredit::where('status_pengajuan', 'Menunggu Konfirmasi')->count();
        $pengajuanDiproses = PengajuanKredit::where('status_pengajuan', 'Diproses')->count();

        return view('dashboard.index', [
            'title' => $title . ' - Dashboard',
            // 'menu' => 'Dashboard',
            // Statistik utama
            'totalMotor' => $totalMotor,
            'perubahanMotor' => $perubahanMotor,
            'totalPelanggan' => $totalPelanggan,
            'perubahanPelanggan' => $perubahanPelanggan,
            'pengajuanBaru' => $pengajuanBaru,
            'totalPengajuan' => $totalPengajuan,
            'perubahanPengajuan' => $perubahanPengajuan,
            
            // Statistik kredit
            'kreditDisetujui' => $kreditDisetujui,
            'totalNilaiKredit' => $totalNilaiKredit,
            'totalDp' => $totalDp,
            'rataKredit' => $rataKredit,
            'totalCicilanBulanan' => $totalCicilanBulanan,
            'pengajuanMenunggu' => $pengajuanMenunggu,
            'pengajuanDiproses' => $pengajuanDiproses
        ]);
    }

    /**
     * Fungsi helper untuk menghitung persentase perubahan
     */
    private function hitungPersentase($totalSekarang, $totalSebelumnya)
    {
        $perubahan = $totalSekarang - $totalSebelumnya;
        $persentase = $totalSebelumnya > 0 
            ? round(($perubahan / $totalSebelumnya) * 100, 2)
            : ($totalSekarang > 0 ? 100 : 0);
            
        return [
            'persentase' => $persentase,
            'naik' => $perubahan >= 0
        ];
    }

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
