{{-- Spinner Start (selalu tampil) --}}
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
{{-- Spinner End --}}

{{-- NAVBAR UNTUK YANG SUDAH LOGIN --}}
@auth('pelanggan')
<div class="container-fluid header-top">
    <div class="container d-flex align-items-center">
        <div class="d-flex align-items-center h-100">
            <a href="#" class="navbar-brand" style="height: 125px;">
                <h1 class="text-primary mb-0"><i class="fas fa-hand-rock me-2"></i>Motorbike Credit</h1>
            </a>
        </div>
        <div class="w-100 h-100">
            <div class="topbar px-0 py-2 d-none d-lg-block" style="height: 45px;"></div>

            <div class="nav-bar px-0 py-lg-0" style="height: 80px;">
                <nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-lg-end" style="height: 80px;">
                    <a href="#" class="navbar-brand-2">
                        <h1 class="text-primary mb-0"><i class="fas fa-hand-rock me-2"></i> Motorbike Credit</h1>
                    </a> 
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav mx-0 mx-lg-auto">
                            <a href="{{ route('/') }}" class="nav-item nav-link @if (@isset($title) and $title === 'Home') active @endif">Home</a>
                            <a href="{{ route('about') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'About') active @endif">About</a>
                            <a href="{{ route('product') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'Product') active @endif">Product</a>
                            <a href="{{ route('blog') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'Blog') active @endif">Blogs</a>
                            <a href="{{ route('contact') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'Contact') active @endif">Contact</a>
                            <a href="{{ route('pengajuan') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'Pengajuan Saya') active @endif">Kredit Saya</a>
                        </div>
                        <div class="d-flex align-items-center ms-lg-3 mt-2 mt-lg-0">
                            {{-- <button class="btn btn-primary btn-md-square me-2" data-bs-toggle="modal" data-bs-target="#searchModal">
                                <i class="fas fa-search"></i>
                            </button> --}}
                            <!-- Tombol Keranjang Belanja -->
                            {{-- <a href="{{route('pengajuan')}}" class="btn btn-primary btn-md-square me-2">
                                <i class="bi bi-cart"></i>
                            </a> --}}
                            <div class="dropdown">
                                <a href="#" class="btn btn-primary dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::guard('pelanggan')->user()->nama_pelanggan }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                    <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                    <li>
                                        <form action="{{ route('log-out') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
@endauth

{{-- NAVBAR UNTUK YANG BELUM LOGIN --}}
@guest('pelanggan')
<div class="container-fluid header-top">
    {{-- <div class="nav-shaps-2"></div> --}}
    <div class="container d-flex align-items-center">
        <div class="d-flex align-items-center h-100">
            <a href="#" class="navbar-brand" style="height: 125px;">
                <h1 class="text-primary mb-0"><i class="fas fa-hand-rock me-2"></i>Motorbike Credit</h1>
            </a>
        </div>
        <div class="w-100 h-100">
            <div class="topbar px-0 py-2 d-none d-lg-block" style="height: 45px;">
                <div class="row gx-0 align-items-center">
                    <div class="col-lg-8 text-center text-lg-center mb-lg-0">
                        <div class="d-flex flex-wrap">
                            <div class="pe-4">
                                <a href="mailto:kreditayesh@gmail.com" class="text-muted small">
                                    <i class="fas fa-envelope text-primary me-2"></i>kreditayesh@gmail.com
                                </a>
                            </div>
                            <div class="pe-0">
                                <a href="#" class="text-muted small">
                                    <i class="fa fa-clock text-primary me-2"></i>Mon - Sat: 8.00 am-7.00 pm
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <div class="d-flex justify-content-end">
                            <div class="d-flex align-items-center small">
                                <a href="{{ route('login')}}" class="login-btn text-body me-3 pe-3">Login</a>
                                <a href="{{ route('register')}}" class="text-body me-3">Register</a>
                            </div>
                            <div class="d-flex pe-3">
                                <a class="btn p-0 text-primary me-3" href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn p-0 text-primary me-3" href="https://www.x.com"><i class="fab fa-twitter"></i></a>
                                <a class="btn p-0 text-primary me-3" href="https://www.instagram.com"><i class="fab fa-instagram"></i></a>
                                <a class="btn p-0 text-primary me-0" href="https://www.linkedin.com"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nav-bar px-0 py-lg-0" style="height: 80px;">
                <nav class="navbar navbar-expand-lg navbar-light d-flex justify-content-lg-end" style="height: 80px;">
                    <a href="#" class="navbar-brand-2">
                        <h1 class="text-primary mb-0"><i class="fas fa-hand-rock me-2"></i> Motorbike Credit</h1>
                    </a> 
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav mx-0 mx-lg-auto">
                            <a href="{{ route('/') }}" class="nav-item nav-link @if (@isset($title) and $title === 'Home') active @endif">Home</a>
                            <a href="{{ route('about') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'About') active @endif">About</a>
                            <a href="{{ route('product') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'Product') active @endif">Product</a>
                            <a href="{{ route('blog') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'Blog') active @endif">Blogs</a>
                            <a href="{{ route('contact') }}" class="nav-item nav-link  @if (@isset($title) and $title === 'Contact') active @endif">Contact</a>
                        </div>
                    </div>
                    <a href="{{route('login')}}" class="btn btn-primary me-2">
                        {{-- <i class="bi bi-cart"></i> --}}Login
                    </a>
                    <a href="{{route('register')}}" class="btn btn-primary me-2">
                        {{-- <i class="bi bi-cart"></i> --}}Register
                    </a>                    
                </nav>
            </div>
        </div>
    </div>
</div>
@endguest

{{-- Modal Search (selalu tampil) --}}
{{-- <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center bg-primary">
                <div class="input-group w-75 mx-auto d-flex">
                    <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                    <span id="search-icon-1" class="btn bg-light border input-group-text p-3">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div> --}}
