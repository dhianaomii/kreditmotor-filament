@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Jadwalkan Pengiriman</h4>  
    </div>
</div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark-blue">Jadwalkan Pengiriman Motor</h2>
        <a href="{{ route('pengajuan') }}" class="btn btn-outline-red">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="product-title-bar">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-truck me-2"></i>
                        Form Penjadwalan Pengiriman
                    </h4>
                    <div>
                        <i class="bi bi-motorcycle text-danger fs-4"></i>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('kirim.store', $pengajuan->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="motor" class="form-label fw-semibold">Motor</label>
                            <input type="text" class="form-control" id="motor" value="{{ $pengajuan->motor->nama_motor ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_pengiriman" class="form-label fw-semibold">Tanggal Pengiriman</label>
                            <input type="date" class="form-control @error('tanggal_pengiriman') is-invalid @enderror" 
                                   id="tanggal_pengiriman" name="tanggal_pengiriman" 
                                   value="{{ old('tanggal_pengiriman') }}" 
                                   min="{{ \Carbon\Carbon::today()->addDays(1)->format('Y-m-d') }}" required>
                            @error('tanggal_pengiriman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat1" class="form-label fw-semibold">Alamat Pengiriman</label>
                            <textarea class="form-control @error('alamat1') is-invalid @enderror" 
                                      id="alamat1" name="alamat1" rows="4" required>{{ old('alamat1') }}</textarea>
                            @error('alamat1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kota1" class="form-label fw-semibold">Kota</label>
                            <input type="text" class="form-control @error('kota1') is-invalid @enderror" 
                                   id="kota1" name="kota1" value="{{ old('kota1') }}" required>
                            @error('kota1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="provinsi1" class="form-label fw-semibold">Provinsi</label>
                            <input type="text" class="form-control @error('provinsi1') is-invalid @enderror" 
                                   id="provinsi1" name="provinsi1" value="{{ old('provinsi1') }}" required>
                            @error('provinsi1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kode_pos1" class="form-label fw-semibold">Kode Pos</label>
                            <input type="text" class="form-control @error('kode_pos1') is-invalid @enderror" 
                                   id="kode_pos1" name="kode_pos1" value="{{ old('kode_pos1') }}" required>
                            @error('kode_pos1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('pengajuan') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-red">
                                <i class="bi bi-check2-circle me-2"></i>Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

<!-- Custom JavaScript -->
<script>
    document.querySelectorAll('.btn-red, .btn-outline-red, .btn-outline-secondary').forEach(link => {
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

    window.addEventListener('load', function () {
        const spinner = document.getElementById('spinner');
        if (spinner) {
            spinner.classList.remove('show');
        }
    });
</script>
@endsection