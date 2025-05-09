@extends('be.master')

@section('navbar')
@include('be.navbar')
@endsection

@section('sidebar')
@include('be.sidebar')
@endsection

@section('content')

<style>
    /* Warna tema utama: Ungu tua */
    :root {
        --primary-color: #4B49AC; /* Indigo tua */
        --primary-hover: #6A0DAD; /* Ungu lebih terang untuk hover */
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-bg: #f8f9fa;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        background: var(--light-bg);
        color: var(--primary-color);
    }

    .nav-tabs .nav-link.active {
        background: var(--primary-color);
        color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container-fluid px-4 mt-4">
    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Nav Tabs -->
            <div class="nav-tabs-wrapper mb-3 rounded-3 shadow-sm">
                <ul class="nav nav-pills nav-tabs" id="dataTab" role="tablist">
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-4 py-3" id="pelanggan-tab" data-bs-toggle="tab" data-bs-target="#pelanggan" type="button" role="tab" aria-controls="pelanggan" aria-selected="true">
                                <i class="ti-user me-2"></i>Data Pelanggan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-3" id="kredit-tab" data-bs-toggle="tab" data-bs-target="#kredit" type="button" role="tab" aria-controls="kredit" aria-selected="false">
                                <i class="ti-credit-card me-2"></i>Kredit
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-3" id="pengajuan-tab" data-bs-toggle="tab" data-bs-target="#pengajuan" type="button" role="tab" aria-controls="pengajuan" aria-selected="false">
                                <i class="ti-clipboard me-2"></i>Pengajuan Kredit
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-3" id="angsuran-tab" data-bs-toggle="tab" data-bs-target="#angsuran" type="button" role="tab" aria-controls="angsuran" aria-selected="false">
                                <i class="ti-money me-2"></i>Angsuran
                            </button>
                        </li>
                    @endif
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'kurir']))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ !in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']) ? 'active' : '' }} px-4 py-3" id="pengiriman-tab" data-bs-toggle="tab" data-bs-target="#pengiriman" type="button" role="tab" aria-controls="pengiriman" aria-selected="{{ !in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']) ? 'true' : 'false' }}">
                                <i class="ti-truck me-2"></i>Pengiriman
                            </button>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="dataTabContent">
                <!-- Tab Pelanggan -->
                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']))
                <div class="tab-pane fade show active" id="pelanggan" role="tabpanel" aria-labelledby="pelanggan-tab">
                    <div class="col-lg grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Data Pelanggan</h4>
                                        <div>
                                            <a href="{{ route('export.pelanggan.pdf') }}" class="btn btn-primary">
                                                Export to PDF
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            <button class="btn btn-primary" onclick="exportTableToExcel('pelanggan-table', 'Data_Pelanggan')">
                                                Export to Excel
                                                <i class="mdi mdi-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="pelanggan-table">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>Status</th>
                                                <th>Nama Pelanggan</th>
                                                <th>Email</th>
                                                <th>No HP</th>
                                                <th>Alamat</th>
                                                <th>Kota</th>
                                                <th>Provinsi</th>
                                                <th>Kode Pos</th>
                                                <th>Foto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pelanggan as $p)
                                            <tr style="text-align: center;">
                                                <td>
                                                    @if($p->is_blocked == 1)
                                                    <label class="badge badge-danger">x</label>
                                                    @else
                                                    <label class="badge badge-success">✓</label>
                                                    @endif
                                                </td>
                                                <td>{{ $p->nama_pelanggan }}</td>
                                                <td>{{ $p->email }}</td>
                                                <td>{{ $p->no_hp }}</td>
                                                <td>{{ $p->alamat1 }}</td>
                                                <td>{{ $p->kota1 }}</td>
                                                <td>{{ $p->provinsi1 }}</td>
                                                <td>{{ $p->kode_pos1 }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/'.$p->foto) }}" style="width: 100px; height: 100px" alt="">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Kredit -->
                <div class="tab-pane fade" id="kredit" role="tabpanel" aria-labelledby="kredit-tab">
                    <div class="col-lg grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Data Kredit</h4>
                                        <div>
                                            <a href="{{ route('export.kredit.pdf') }}" class="btn btn-primary">
                                                Export to PDF
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            <button class="btn btn-primary" onclick="exportTableToExcel('kredit-table', 'Data_Kredit')">
                                                Export to Excel
                                                <i class="mdi mdi-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="kredit-table">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>Status</th>
                                                <th>Nama Pelanggan</th>
                                                <th>Metode Pembayaran</th>
                                                <th>Tanggal Mulai Kredit</th>
                                                <th>Tanggal Selesai Kredit</th>
                                                <th>Keterangan Kredit</th>
                                                <th>Bukti Bayar DP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kredit as $k)
                                            <tr style="text-align: center;">
                                                <td>
                                                    @if($k->status_kredit == 'Macet')
                                                    <label class="badge badge-danger">Macet</label>
                                                    @elseif($k->status_kredit == 'Dicicil')
                                                    <label class="badge badge-warning">Dicicil</label>
                                                    @else
                                                    <label class="badge badge-success">Lunas</label>
                                                    @endif
                                                </td>
                                                <td>{{ $k->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                                                <td>{{ $k->MetodePembayaran->metode_pembayaran }}</td>
                                                <td>{{ $k->tgl_mulai_kredit }}</td>
                                                <td>{{ $k->tgl_selesai_kredit }}</td>
                                                <td>{{ $k->keterangan_status_kredit }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/'. $k->url_bukti_bayar) }}" style="width: 100px; height: 100px" alt="">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Pengajuan Kredit -->
                <div class="tab-pane fade" id="pengajuan" role="tabpanel" aria-labelledby="pengajuan-tab">
                    <div class="col-lg grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Data Pengajuan Kredit</h4>
                                        <div>
                                            <a href="{{ route('export.pengajuan.pdf') }}" class="btn btn-primary">
                                                Export to PDF
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            <button class="btn btn-primary" onclick="exportTableToExcel('pengajuan-table', 'Data_Pengajuan')">
                                                Export to Excel
                                                <i class="mdi mdi-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="pengajuan-table">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>Status Pengajuan</th>
                                                <th>Tanggal</th>
                                                <th>Nama Pelanggan</th>
                                                <th>Motor</th>
                                                <th>Harga Kredit</th>
                                                <th>DP</th>
                                                <th>Lama Cicilan</th>
                                                <th>Asuransi</th>
                                                <th>Cicilan/Bulan</th>
                                                <th>Foto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuan as $i)
                                            <tr style="text-align: center;">
                                                <td>
                                                    @if($i->status_pengajuan == 'Bermasalah' || $i->status_pengajuan == 'Dibatalkan Pembeli' || $i->status_pengajuan == 'Dibatalkan Penjual')
                                                    <label class="badge badge-danger">{{ $i->status_pengajuan }}</label>
                                                    @elseif($i->status_pengajuan == 'Diterima' || $i->status_pengajuan == 'Selesai')
                                                    <label class="badge badge-success">{{ $i->status_pengajuan }}</label>
                                                    @else
                                                    <label class="badge badge-warning">{{ $i->status_pengajuan }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $i->tgl_pengajuan_kredit }}</td>
                                                <td>{{ $i->Pelanggan->nama_pelanggan }}</td>
                                                <td>{{ $i->Motor->nama_motor }}</td>
                                                <td>{{ $i->harga_kredit }}</td>
                                                <td>{{ $i->dp }}</td>
                                                <td>{{ $i->JenisCicilan->lama_cicilan }} Bulan</td>
                                                <td>{{ $i->biaya_asuransi_perbulan }}</td>
                                                <td>{{ $i->cicilan_perbulan }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/'.$i->url_foto) }}" style="width: 100px; height: 100px" alt="">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Angsuran -->
                <div class="tab-pane fade" id="angsuran" role="tabpanel" aria-labelledby="angsuran-tab">
                    <div class="col-lg grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Data Angsuran</h4>
                                        <div>
                                            <a href="{{ route('export.angsuran.pdf') }}" class="btn btn-primary">
                                                Export to PDF
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            <button class="btn btn-primary" onclick="exportTableToExcel('angsuran-table', 'Data_Angsuran')">
                                                Export to Excel
                                                <i class="mdi mdi-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="angsuran-table">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>Nama Pelanggan</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Angsuran Ke</th>
                                                <th>Total Bayar</th>
                                                <th>Keterangan</th>
                                                <th>Bukti Angsuran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($angsuran as $a)
                                            <tr style="text-align: center;">
                                                <td>{{ $a->Kredit->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                                                <td>{{ $a->tgl_bayar }}</td>
                                                <td>{{ $a->angsuran_ke }}</td>
                                                <td>{{ $a->total_bayar }}</td>
                                                <td>{{ $a->keterangan }} Bulan</td>
                                                <td>
                                                    <img src="{{ asset('storage/'. $a->bukti_angsuran) }}" style="width: 100px; height: 100px" alt="">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab Pengiriman -->
                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'kurir']))
                <div class="tab-pane fade {{ !in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']) ? 'show active' : '' }}" id="pengiriman" role="tabpanel" aria-labelledby="pengiriman-tab">
                    <div class="col-lg grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <h4 class="mb-0">Data Pengiriman</h4>
                                        <div>
                                            <a href="{{ route('export.pengiriman.pdf') }}" class="btn btn-primary">
                                                Export to PDF
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            <button class="btn btn-primary" onclick="exportTableToExcel('pengiriman-table', 'Data_Pengiriman')">
                                                Export to Excel
                                                <i class="mdi mdi-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="pengiriman-table">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>Status Kirim</th>
                                                <th>No Invoice</th>
                                                <th>Nama Pelanggan</th>
                                                <th>Nama Kurir</th>
                                                <th>No HP</th>
                                                <th>Tanggal Pengiriman</th>
                                                <th>Tanggal Tiba</th>
                                                <th>Keterangan</th>
                                                <th>Foto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengiriman as $i)
                                            <tr style="text-align: center;">
                                                <td>
                                                    @if($i->status_kirim == 'Sedang Dikirim')
                                                    <label class="badge badge-warning">{{ $i->status_kirim }}</label>
                                                    @else
                                                    <label class="badge badge-success">{{ $i->status_kirim }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $i->no_invoice }}</td>
                                                <td>{{ $i->Kredit->PengajuanKredit->Pelanggan->nama_pelanggan }}</td>
                                                <td>{{ $i->nama_kurir }}</td>
                                                <td>{{ $i->telpon_kurir }}</td>
                                                <td>{{ $i->tgl_kirim }}</td>
                                                <td>{{ $i->tgl_tiba }}</td>
                                                <td>{{ $i->keterangan }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/'.$i->bukti_foto) }}" style="width: 100px; height: 100px" alt="">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('be.footer')
@endsection