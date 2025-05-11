@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<style>
    .pagination {
    margin: 20px 0;
}

.page-item.active .page-link {
    background-color: #d33;
    border-color: #d33;
}

.page-link {
    color: #d33;
    padding: 8px 16px;
    margin: 0 4px;
    border-radius: 4px;
}

.page-link:hover {
    color: #a00;
    background-color: #f8f9fa;
}

.page-item.disabled .page-link {
    color: #6c757d;
}

.page-item.active .page-link {
    color: white;
}
</style>

<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Pengajuan Kredit Motor</h4>  
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark-blue">Pengajuan Kredit Motor</h2>
        <a href="{{ route('product') }}" class="btn btn-outline-red">
            <i class="bi bi-arrow-left me-2"></i>Kembali Belanja
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="product-title-bar">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-clipboard-check me-2"></i>
                        List Pengajuan Kredit Motor
                    </h4>
                    <div>
                        <i class="bi bi-cart3 text-danger fs-4"></i>
                    </div>
                </div>
                <div class="card-body">
                    <!-- SweetAlert untuk notifikasi -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            @if (session('success'))
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: '{{ session('success') }}',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#3085d6'
                                });
                            @endif
                            @if (session('error'))
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: '{{ session('error') }}',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#d33'
                                });
                            @endif
                        });
                    </script>

                    @if($pengajuan->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Status Pengajuan</th>
                                        <th style="width: 15%;">Motor</th>
                                        <th style="width: 15%">Harga Kredit</th>
                                        <th style="width: 12%">Lama Cicilan</th>
                                        <th style="width: 15%">DP</th>
                                        <th style="width: 15%">Cicilan Perbulan</th>
                                        <th style="width: 15%">Biaya Asuransi Perbulan</th>
                                        <th style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuan as $item)
                                        <tr>
                                            <td>
                                                @if ($item->status_pengajuan == 'Dibatalkan Pembeli' || $item->status_pengajuan == 'Dibatalkan Penjual' || $item->status_pengajuan == 'Bermasalah')
                                                    <label class="badge bg-danger">{{ $item->status_pengajuan }}</label>
                                                @elseif ($item->status_pengajuan == 'Diterima' || $item->status_pengajuan == 'Selesai')
                                                    <label class="badge bg-success">{{ $item->status_pengajuan }}</label>
                                                @elseif ($item->status_pengajuan == 'Menunggu Konfirmasi' || $item->status_pengajuan == 'Diproses' || $item->status_pengajuan == 'Menunggu Pembayaran')
                                                    <label class="badge bg-warning">{{ $item->status_pengajuan }}</label>
                                                @endif
                                            </td>
                                            <td class="fw-bold">{{ $item->motor->nama_motor ?? '-' }}</td>
                                            <td>IDR {{ number_format($item->harga_kredit, 2, ',', '.') }}</td>
                                            <td>{{ $item->jenisCicilan->lama_cicilan ?? '-' }} bulan</td>
                                            <td>IDR {{ number_format($item->dp, 2, ',', '.') }}</td>
                                            <td class="fw-bold text-danger">IDR {{ number_format($item->cicilan_perbulan, 2, ',', '.') }}</td>
                                            <td>IDR {{ number_format($item->biaya_asuransi_perbulan, 2, ',', '.') }}</td>
                                            <td>
                                                <div class="d-flex gap-2 text-center">
                                                    @if($item->status_pengajuan == 'Menunggu Konfirmasi' || $item->status_pengajuan == 'Diproses')
                                                        <form action="{{ route('pengajuan.cancel', $item->id) }}" method="POST" class="cancel-form" id="cancel-form-{{ $item->id }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="keterangan_status_pengajuan" id="keterangan_status_pengajuan-{{ $item->id }}">
                                                            <button type="button" class="btn btn-outline-danger cancel-btn" data-id="{{ $item->id }}">
                                                                <i class="bi bi-x-circle me-2"></i>Batalkan
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($item->status_pengajuan == 'Menunggu Pembayaran')
                                                        <a href="{{ route('pengajuan.create', $item->id) }}" class="btn btn-outline-danger">
                                                            <i class="bi bi-credit-card me-2"></i>Bayar DP
                                                        </a>
                                                    @elseif ($item->status_pengajuan == 'Diterima')
                                                        <a href="{{ route('cicilan', $item->id) }}" class="btn btn-red">
                                                            <i class="bi bi-info-circle me-2"></i>Lihat Detail
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                             <!-- Pagination Links -->
                             <div class="d-flex justify-content-center mt-4">
                                {{ $pengajuan->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 mb-2">List pengajuan kredit Anda kosong</h5>
                            <p class="text-muted mb-4">Silakan pilih motor yang ingin Anda ajukan kredit</p>
                            <a href="{{ route('product') }}" class="btn btn-red">
                                <i class="bi bi-motorcycle me-2"></i>Lihat Katalog Motor
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Tambahkan tabel pengiriman di bawah card pengajuan -->
    <div class="card mb-4 mt-4">
        <div class="product-title-bar">
            <h4 class="mb-0 text-white">
                <i class="bi bi-truck me-2"></i>
                List Pengiriman
            </h4>
            <div>
                <i class="bi bi-box-seam text-danger fs-4"></i>
            </div>
        </div>
        <div class="card-body">
            @if($pengiriman->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 15%;">No. Invoice</th>
                                <th style="width: 20%;">Motor</th>
                                <th style="width: 15%;">Tanggal Pengiriman</th>
                                <th style="width: 25%;">Alamat Pengiriman</th>
                                <th style="width: 15%;">Status Pengiriman</th>
                                <th style="width: 10%;">Bukti Pengiriman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengiriman as $item)
                                <tr>
                                    <td>{{ $item->no_invoice ?? '-' }}</td>
                                    <td class="fw-bold">
                                        {{ $item->kredit->PengajuanKredit->motor->nama_motor ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $item->tgl_kirim ? \Carbon\Carbon::parse($item->tgl_kirim)->format('d M Y') : '-' }}
                                    </td>
                                    <td>{{Auth::user()->alamat1}}, {{Auth::user()->kota1}}, {{Auth::user()->provinsi1}}, {{Auth::user()->kode_pos1}}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status_kirim == 'Tiba Ditujuan' ? 'success' : ($item->status_kirim == 'Sedang Dikirim' ? 'primary' : 'primary') }} p-2">
                                            {{ $item->status_kirim ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->bukti_foto)
                                            <!-- Tombol untuk memicu modal -->
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#buktiModal{{ $item->id }}">
                                                <i class="bi bi-file-earmark-image me-2"></i> Lihat Bukti
                                            </button>
                                    
                                            <!-- Modal untuk menampilkan bukti pengiriman -->
                                            <div class="modal fade" id="buktiModal{{ $item->id }}" tabindex="-1" aria-labelledby="buktiModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="buktiModalLabel{{ $item->id }}">Bukti Pengiriman - {{ $item->no_invoice ?? '-' }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ asset('storage/' . $item->bukti_foto) }}" alt="Bukti Pengiriman" class="img-fluid" style="max-height: 500px; object-fit: contain;">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Pagination Links -->
                                            {{-- <div class="d-flex justify-content-center mt-4">
                                                {{ $pengajuan->links('vendor.pagination.bootstrap-4') }}
                                            </div> --}}
                                        @else
                                            <span class="text-muted">Belum Ada Bukti Pengiriman</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-truck text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 mb-2">List pengiriman Anda kosong</h5>
                    <p class="text-muted mb-4">Belum ada pengiriman yang dijadwalkan.</p>
                    <a href="{{ route('product') }}" class="btn btn-red">
                        <i class="bi bi-motorcycle me-2"></i>Lihat Katalog Motor
                    </a>
                </div>
            @endif
        </div>
    </div>

  <!-- SweetAlert untuk konfirmasi pembatalan dengan input alasan -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function () {
                const pengajuanId = this.getAttribute('data-id');
                Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Pembatalan',
                    text: 'Masukkan alasan pembatalan pengajuan ini:',
                    input: 'textarea',
                    inputPlaceholder: 'Tuliskan alasan pembatalan...',
                    inputAttributes: {
                        'required': true,
                        'minlength': 10
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Tidak',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    preConfirm: (keterangan) => {
                        if (!keterangan || keterangan.length < 10) {
                            Swal.showValidationMessage('Alasan harus diisi minimal 10 karakter.');
                            return false;
                        }
                        return keterangan;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById(`cancel-form-${pengajuanId}`);
                        const keteranganInput = document.getElementById(`keterangan_status_pengajuan-${pengajuanId}`);
                        keteranganInput.value = result.value;
                        form.submit();
                    }
                });
            });
        });
    });
</script>

    <!-- Custom JavaScript -->
    <script>
        // Efek transisi fade-out untuk semua link dengan kelas 'read-more' dan tombol checkout
        document.querySelectorAll('.read-more, .btn-red, .btn-outline-red').forEach(link => {
            link.addEventListener('click', function(event) {
                if (this.tagName === 'A' && !this.closest('form')) {
                    event.preventDefault();
                    const href = this.getAttribute('href');
                    document.body.classList.add('fade-out');
                    setTimeout(() => {
                        window.location.href = href;
                    }, 500);
                }
            });
        });

        // Script untuk menghilangkan spinner setelah halaman selesai dimuat
        window.addEventListener('load', function () {
            const spinner = document.getElementById('spinner');
            if (spinner) {
                spinner.classList.remove('show');
            }
        });
    </script>
    <!-- Custom JavaScript -->
    <script>
        // Efek transisi fade-out untuk semua link dengan kelas 'read-more' dan tombol checkout
        document.querySelectorAll('.read-more, .btn-red, .btn-outline-red').forEach(link => {
            link.addEventListener('click', function(event) {
                // Hanya terapkan efek jika link adalah <a> atau bukan tombol hapus
                if (this.tagName === 'A' && !this.closest('form')) {
                    event.preventDefault();
                    const href = this.getAttribute('href');
                    document.body.classList.add('fade-out');
                    setTimeout(() => {
                        window.location.href = href;
                    }, 500);
                }
            });
        });

        // Script untuk menghilangkan spinner setelah halaman selesai dimuat
        window.addEventListener('load', function () {
            const spinner = document.getElementById('spinner');
            if (spinner) {
                spinner.classList.remove('show');
            }
        });
    </script>
@endsection