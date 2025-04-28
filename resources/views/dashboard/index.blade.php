@extends('be.master')

@section('navbar')
@include('be.navbar')
@endsection

@section('sidebar')
@include('be.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Card Total Motor -->
        <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">Total Motor Tersedia</p>
                    <p class="fs-30 mb-2">{{ number_format($totalMotor, 0) }}</p>
                    <p>
                        {{ $perubahanMotor['persentase'] }}% (30 hari terakhir)
                        <i class="ti-arrow-{{ $perubahanMotor['naik'] ? 'up' : 'down' }}"></i>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Card Total Pelanggan -->
        <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <p class="mb-4">Total Pelanggan</p>
                    <p class="fs-30 mb-2">{{ number_format($totalPelanggan, 0) }}</p>
                    <p>
                        {{ $perubahanPelanggan['persentase'] }}% (30 hari terakhir)
                        <i class="ti-arrow-{{ $perubahanPelanggan['naik'] ? 'up' : 'down' }}"></i>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Card Pengajuan Hari Ini -->
        <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-light-blue">
                <div class="card-body">
                    <p class="mb-4">Pengajuan Hari Ini</p>
                    <p class="fs-30 mb-2">{{ number_format($pengajuanBaru, 0) }}</p>
                    <p>Total Pengajuan : {{ number_format($totalPengajuan, 0) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Card Kredit Disetujui -->
        <div class="col-md-6 mb-4 stretch-card transparent">
            <div class="card card-light-danger">
                <div class="card-body">
                    <p class="mb-4">Kredit Disetujui</p>
                    <p class="fs-30 mb-2">{{ number_format($kreditDisetujui, 0) }}</p>
                    <p>Total Nilai Kredit: Rp{{ number_format($totalNilaiKredit, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tambahkan bagian grafik dan tabel jika diperlukan -->
</div>
@endsection

@section('footer')
@include('be.footer')
@endsection
