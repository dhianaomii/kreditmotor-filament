@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
    <div class="container py-4  ">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('product') }}" class="btn btn-outline-danger border-2 fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Back to Products
            </a>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <!-- Carousel Section -->
                    <div class="position-relative">
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset ('storage/'. $motor->foto1)}}" class="d-block w-100" alt="{{ $motor->nama_motor }}" style="height: 500px; object-fit: cover;">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset ('storage/'. $motor->foto2)}}" class="d-block w-100" alt="{{ $motor->nama_motor }}" style="height: 500px; object-fit: cover;">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset ('storage/'. $motor->foto3)}}" class="d-block w-100" alt="{{ $motor->nama_motor }}" style="height: 500px; object-fit: cover;">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-danger p-2">NEW</span>
                        </div>
                    </div>
                    
                    <!-- Thumbnail navigation -->
                    <div class="d-flex gap-2 p-2">
                        <div class="thumbnail-item border  border-danger" data-bs-target="#productCarousel" data-bs-slide-to="0">
                            <img src="{{ asset ('storage/'. $motor->foto1)}}" class="img-thumbnail" alt="Thumbnail 1" style="width: 70px; height: 70px; object-fit: cover;">
                        </div>
                        <div class="thumbnail-item border border-transparent" data-bs-target="#productCarousel" data-bs-slide-to="1">
                            <img src="{{ asset ('storage/'. $motor->foto2)}}" class="img-thumbnail" alt="Thumbnail 2" style="width: 70px; height: 70px; object-fit: cover;">
                        </div>
                        <div class="thumbnail-item border border-transparent" data-bs-target="#productCarousel" data-bs-slide-to="2">
                            <img src="{{ asset ('storage/'. $motor->foto3)}}" class="img-thumbnail" alt="Thumbnail 3" style="width: 70px; height: 70px; object-fit: cover;">
                        </div>
                    </div>
                    
                    <div class="bg-dark d-flex justify-content-between align-items-center p-3">
                        <h4 class="mb-0 text-white">{{$motor->nama_motor}} {{$motor->JenisMotor->jenis}} - {{$motor->JenisMotor->merk}}</h4>
                        <i class="bi bi-cart text-danger"></i>
                    </div>
                    <div class="card-body">
                        <div class="fs-3 fw-bold text-secondary mb-4">Rp {{ number_format($motor->harga_jual, 0, ',', '.') }}</div>
                        
                        <div class="mb-4">
                            <h5 class="text-dark fw-bold  border-danger pb-2 d-inline-block">Product Description</h5>
                            <p>
                                {{ $motor->nama_motor}} {{$motor->JenisMotor->jenis}} - {{$motor->JenisMotor->merk}} 
                                {{ $motor->deskripsi_motor }}
                            </p>
                            <p>{{$motor->JenisMotor->deskripsi_jenis}}</p>
                        </div>
                        
                        <div class="d-flex align-items-center mb-2">
                            <label class="fw-bold me-3">Stock:</label>
                            @if($motor->stok > 10)
                                <span class="badge bg-success">{{ $motor->stok }} Available</span>
                            @elseif($motor->stok > 0)
                                <span class="badge bg-warning text-dark">{{ $motor->stok }} Limited Stock</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </div>

                        <div class="action-buttons mt-4 mb-2">
                            <div class="row gx-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <button class="btn btn-outline-danger border-2 fw-medium w-100 py-3 d-flex align-items-center justify-content-center" 
                                            {{ $motor->stok == 0 ? 'disabled' : '' }}
                                            data-bs-toggle="tooltip" 
                                            title="{{ $motor->stok == 0 ? 'Produk tidak tersedia' : 'Tambahkan ke keranjang' }}">
                                        <i class="bi bi-cart-plus fs-5 me-2"></i>
                                        <span class="fw-medium">{{ $motor->stok == 0 ? 'Out of Stock' : 'Tambahkan ke Keranjang' }}</span>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    @if($motor->stok > 0)
                                        @auth
                                            <a href="{{ route('product.create', $motor->id) }}" class="btn btn-danger fw-bold w-100 py-3 d-flex align-items-center justify-content-center shadow-sm">
                                                <i class="bi bi-calendar-check fs-5 me-2"></i>
                                                <span class="fw-medium">Ajukan Sekarang</span>
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-danger fw-bold w-100 py-3 d-flex align-items-center justify-content-center shadow-sm" onclick="showLoginAlert()">
                                                <i class="bi bi-calendar-check fs-5 me-2"></i>
                                                <span class="fw-medium">Ajukan Sekarang</span>
                                            </button>
                                        @endauth
                                    @else
                                        <button type="button" class="btn btn-danger fw-bold w-100 py-3 d-flex align-items-center justify-content-center shadow-sm" onclick="showStockAlert()">
                                            <i class="bi bi-calendar-check fs-5 me-2"></i>
                                            <span class="fw-medium">Ajukan Sekarang</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4 shadow-sm">
                    <div class="bg-dark p-3">
                        <h4 class="mb-0 text-white">Specifications</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-start text-secondary ps-3" style="width: 40%; border-right: 1px solid #dee2e6;">Jenis Motor</td>
                                        <td class="ps-3">{{ $motor->JenisMotor->jenis }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-start text-secondary ps-3" style="border-right: 1px solid #dee2e6;">Merk Motor</td>
                                        <td class="ps-3">{{ $motor->JenisMotor->merk }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-start text-secondary ps-3" style="border-right: 1px solid #dee2e6;">Nama Motor</td>
                                        <td class="ps-3">{{ $motor->nama_motor }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-start text-secondary ps-3" style="border-right: 1px solid #dee2e6;">Warna Motor</td>
                                        <td class="ps-3">{{ $motor->warna }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-start text-secondary ps-3" style="border-right: 1px solid #dee2e6;">Tahun Produksi</td>
                                        <td class="ps-3">{{ $motor->tahun_produksi }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-start text-secondary ps-3" style="border-right: 1px solid #dee2e6;">Kapasitas Mesin</td>
                                        <td class="ps-3">{{ $motor->kapasitas_mesin }} CC</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="bg-dark p-3">
                        <h4 class="mb-0 text-white">Related Products</h4>
                    </div>
                    @foreach ($data as $i )  
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="card h-100">
                                    <div class="row g-0">
                                        <div class="col-4">
                                            <img src="{{ asset ('storage/'. $i->foto1)}}" class="img-fluid rounded-start" alt="Related Product 1" style="width: 120px; height: 120px;">
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-2">
                                                <h6 class="mb-1">{{ $i->nama_motor }}</h6>
                                                <div class="small text-muted mb-2">{{ $i->JenisMotor->jenis }}</div>
                                                <div class="fw-bold">Rp {{ number_format($i->harga_jual, 0, ',', '.') }}</div>
                                                <a href="{{route('product.show', $i->id)}}" class="btn btn-dark btn-sm mt-2 d-block text-center text-danger fw-bold text-uppercase">Read More <i class="bi bi-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Pengajuan Kredit Berhasil!',
            text: '{{ session('success') }} Lihat detail.',
            showCancelButton: true,
            confirmButtonText: 'Lihat Detail',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ganti dengan route menuju halaman detail pengajuan
                window.location.href = '{{ route('pengajuan') }}'; // Sesuaikan route
            } else {
                window.location.href = '{{ route('product.show', $motor->id) }}';
            }
        });
    @endif
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <script>
        function showLoginAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Anda Belum Login',
                text: 'Anda harus login terlebih dahulu untuk mengajukan kredit.',
                showCancelButton: true,
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}'; // Redirect to login page
                }
            });
        }
    </script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript for thumbnail interaction and fade effects -->
    <script>
        // Add fade transition to page navigation
        document.querySelectorAll('.btn-dark, .btn-outline-danger').forEach(link => {
            if (link.getAttribute('href')) {
                link.addEventListener('click', function(event) {
                    if (!this.hasAttribute('disabled')) {
                        event.preventDefault();
                        const href = this.getAttribute('href');
                        document.body.classList.add('opacity-0');
                        document.body.style.transition = 'opacity 0.5s ease-out';
                        
                        setTimeout(() => {
                            window.location.href = href;
                        }, 500);
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get all thumbnail elements
            const thumbnails = document.querySelectorAll('.thumbnail-item');
            
            // Add click event to each thumbnail
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Remove border from all thumbnails
                    thumbnails.forEach(thumb => {
                        thumb.classList.remove('border-danger');
                        thumb.classList.add('border-transparent');
                    });
                    
                    // Add border to clicked thumbnail
                    this.classList.remove('border-transparent');
                    this.classList.add('border-danger', 'border-2');
                });
            });
            
            // Update thumbnail active state when carousel slides
            const carouselEl = document.getElementById('productCarousel');
            carouselEl.addEventListener('slid.bs.carousel', function(event) {
                // Remove border from all thumbnails
                thumbnails.forEach(thumb => {
                    thumb.classList.remove('border-danger');
                    thumb.classList.add('border-transparent');
                });
                
                // Add border to corresponding thumbnail
                const activeIndex = event.to;
                const activeThumb = document.querySelector(`.thumbnail-item[data-bs-slide-to="${activeIndex}"]`);
                if (activeThumb) {
                    activeThumb.classList.remove('border-transparent');
                    activeThumb.classList.add('border-danger', 'border-2');
                }
            });
        });

        // Initialize Bootstrap tooltips if needed
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
@endsection
