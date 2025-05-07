@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Blog Detail</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog') }}">Blog</a></li>
                <li class="breadcrumb-item active text-primary">{{ $blog->judul }}</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

    <!-- Blog Detail Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0 fw-bold text-dark-blue">{{ $blog->judul }}</h2>
                    <a href="{{ route('blog') }}" class="btn btn-outline-red">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
                <!-- Blog Detail Content Start -->
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="blog-detail-item">
                        <div class="rounded overflow-hidden mb-4">
                            <img class="img-fluid w-100" src="{{ asset('storage/'. $blog->image) }}" alt="Blog Detail Image">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta d-flex justify-content-between py-3 border-bottom mb-4">
                                <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>{{ $blog->user->name }}</h6>
                                <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>{{ $blog->publish_at }}</h6>
                            </div>
                            <h2 class="mb-4">{{ $blog->judul }}</h2>
                            <div class="blog-text">
                                {!! nl2br(e($blog->content)) !!}
                            </div>

                            @if($blog->sumber)
                            <div class="mt-4 pt-3 border-top">
                                <h5>Source:</h5>
                                <a href="{{ $blog->sumber }}" target="_blank" class="text-primary">{{ $blog->sumber }}</a>
                            </div>
                            @endif
                            
                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex align-items-center">
                                    <h5 class="me-3 mb-0">Share:</h5>
                                    <div class="d-flex">
                                        <a class="btn btn-square btn-primary me-2" href=""><i class="bi bi-facebook"></i></a>
                                        <a class="btn btn-square btn-primary me-2" href=""><i class="bi bi-twitter"></i></a>
                                        <a class="btn btn-square btn-primary me-2" href=""><i class="bi bi-instagram"></i></a>
                                        <a class="btn btn-square btn-primary" href=""><i class="bi bi-linkedin"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blog Detail Content End -->
                
                <!-- Sidebar Start -->
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="bg-light rounded p-4 mb-4">
                        <h4 class="mb-3">Recent Posts</h4>
                        @foreach ($recentBlogs as $recent)
                        <div class="d-flex mb-3">
                            <img class="img-fluid rounded" src="{{ asset('storage/'. $recent->image) }}" alt="Recent Post" style="width: 80px; height: 80px; object-fit: cover;">
                            <a href="{{ route('blog.detail', $recent->id) }}" class="h5 d-flex align-items-center ms-3">{{ $recent->judul }}</a>
                        </div>
                        @endforeach
                    </div>
                    
                </div>
                <!-- Sidebar End -->
            </div>
            
            <!-- Related Posts Start -->
            <div class="row g-4 mt-5 wow fadeInUp" data-wow-delay="0.5s">
                <div class="col-12">
                    <h3 class="mb-4">Related Posts</h3>
                </div>
                
                @foreach ($relatedBlogs as $related)
                <div class="col-md-4">
                    <div class="blog-item">
                        <div class="rounded overflow-hidden">
                            <img class="img-fluid w-100" src="{{ asset('storage/'. $related->image) }}" alt="Blog Image">
                            <div class="blog-overlay">
                                <a class="btn btn-square btn-primary" href="{{ $related->sumber }}"><i class="bi bi-link"></i></a>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta d-flex justify-content-between py-3">
                                <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>{{ $related->user->name }}</h6>
                                <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>{{ $related->publish_at }}</h6>
                            </div>
                            <h5 class="mb-3">{{ $related->judul }}</h5>
                            @if(strlen($related->content) > 100)
                                <p class="mb-4">{{ substr($related->content, 0, 100) . '...' }}</p>
                            @else
                                <p>{{ $related->content }}</p>
                            @endif
                            <a href="{{ route('blog.detail', $related->id) }}" class="btn btn-primary px-5 rounded-pill">Read More</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Related Posts End -->
        </div>
    </div>
    <!-- Blog Detail End -->

   
@endsection

@section('footer')
    @include('fe.footer')
@endsection