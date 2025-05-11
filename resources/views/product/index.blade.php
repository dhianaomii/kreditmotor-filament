@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<!-- Header Start -->
<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h4 class="text-primary">Our Product</h4>
        <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Check out our motorbike</h4> 
    </div>
</div>
<!-- Header End -->

<!-- Blog Start -->
<div class="container-fluid blog py-5">
    <!-- Filter Start -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="d-flex align-items-center">
                <!-- Form Filter Jenis Motor -->
                <form method="GET" action="{{ route('product') }}" class="flex-grow-1">
                    <div class="input-group rounded-pill overflow-hidden shadow">
                        <label class="input-group-text border-0 bg-primary text-white px-4 py-3" for="jenis_motor">
                            <i class="bi bi-funnel-fill me-2"></i>
                            Filter Jenis
                        </label>
                        <select name="jenis_motor" id="jenis_motor" class="form-select border-0 py-3" onchange="this.form.submit()">
                            <option value="">Semua Jenis Motor</option>
                            @foreach ($jenisMotors as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('jenis_motor') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Filter End -->

    <!-- Modal Pencarian -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Cari Motor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('product') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="searchInput" class="form-label">Nama Motor atau Merk</label>
                            <input type="text" name="search" id="searchInput" class="form-control" 
                                   placeholder="Ketik nama motor atau merk..." 
                                   value="{{ request('search') }}">
                            <!-- Pertahankan filter jenis motor jika ada -->
                            @if (request('jenis_motor'))
                                <input type="hidden" name="jenis_motor" value="{{ request('jenis_motor') }}">
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Motor -->
    <div class="container py-5">
        <div class="row g-4">
            @forelse ($data as $product)
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="blog-item h-100 d-flex flex-column {{ $product->stok == 0 ? 'bg-secondary text-muted' : '' }}">
                        <div class="blog-img position-relative">
                            <a href="{{ $product->stok > 0 ? route('product.show', $product->id) : '#' }}">
                                <img src="{{ asset('storage/' . $product->foto1) }}" class="img-fluid w-100" alt="{{ $product->nama_motor }}">
                                @if ($product->stok == 0)
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Out of Stock</span>
                                @endif
                            </a>
                        </div>
                        <div class="blog-content p-4 flex-grow-1 d-flex flex-column">
                            <div class="blog-comment d-flex justify-content-between py-2 px-3 mb-2">
                                <div class="small {{ $product->stok == 0 ? 'text-muted' : 'text-white' }}">
                                    @php
                                        $motorDetails = $product->nama_motor . ' ' . $product->JenisMotor->jenis . ' - ' . $product->JenisMotor->merk;
                                        $maxLength = 20; // Adjust as needed
                                    @endphp
                                    @if(strlen($motorDetails) > $maxLength)
                                        {{ substr($motorDetails, 0, strrpos(substr($motorDetails, 0, $maxLength), ' ')) . '...' }}
                                    @else
                                        {{ $motorDetails }}
                                    @endif
                                </div>
                                <div class="small"><i class="fa fa-calendar text-primary me-2"></i></div>
                            </div>
                            @php
                                $deskripsi = $product->deskripsi_motor;
                                $deskripsiMaxLength = 50; // Adjust as needed
                            @endphp
                            <p class="mb-2 {{ $product->stok == 0 ? 'text-muted' : 'text-black' }}">
                                @if(strlen($deskripsi) > $deskripsiMaxLength)
                                    {{ substr($deskripsi, 0, strrpos(substr($deskripsi, 0, $deskripsiMaxLength), ' ')) . '...' }}
                                @else
                                    {{ $deskripsi }}
                                @endif
                            </p>
                            <div class="d-flex align-items-center mb-2">
                                @if ($product->stok > 10)
                                    <span class="badge bg-success">{{ $product->stok }} Available</span>
                                @elseif ($product->stok > 0)
                                    <span class="badge bg-warning text-dark">{{ $product->stok }} Limited Stock</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </div>
                            <p class="mb-4 text-lg {{ $product->stok == 0 ? 'text-muted' : 'text-black' }} fw-bold">
                                Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
                            </p>
                            <div class="mt-auto">
                                @if ($product->stok > 0)
                                    <a href="{{ route('product.show', $product->id) }}" class="btn btn-dark py-2 w-100">
                                        <span class="me-2">Ajukan Kredit</span>
                                        <i class="bi bi-arrow-right-circle"></i>
                                    </a>
                                @else
                                    <button class="btn btn-secondary py-2 w-100" disabled title="Motor ini tidak tersedia">
                                        <span class="me-2">Unavailable</span>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if($data->hasPages())
                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $data->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12">
                    <p class="text-center">Tidak ada motor yang ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // SweetAlert untuk stok 0
    document.querySelectorAll('.blog-item.bg-secondary .blog-img a, .blog-item.bg-secondary .btn-secondary:disabled').forEach(element => {
        element.addEventListener('click', function (e) {
            if (this.tagName === 'BUTTON' || this.getAttribute('href') === '#') {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Habis',
                    text: 'Motor ini sedang tidak tersedia. Silakan pilih motor lain.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            }
        });
    });
});
</script>
@endsection

@section('footer')
    @include('fe.footer')
@endsection