@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Blog & Articles</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active text-primary">Blog</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

    <!-- Blog Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-4">  
                <!-- Blog List Start -->
                <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="row g-4">
                        @foreach ($blog as $i )
                        <div class="col-md-6">
                            <div class="blog-item">
                                <div class="rounded overflow-hidden">
                                    <img class="img-fluid w-100" src="{{asset('storage/'. $i->image)}}" alt="Blog Image">
                                    <div class="blog-overlay">
                                        <a class="btn btn-square btn-primary" href="{{$i->sumber}}"><i class="bi bi-link"></i></a>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta d-flex justify-content-between py-3">
                                        <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>{{$i->user->name}}</h6>
                                        <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>{{$i->publish_at}}</h6>
                                    </div>
                                    <h5 class="mb-3">{{$i->judul}}</h5>
                                    @if(strlen($i['content']) > 100)
                                        <p class="mb-4">
                                            {{substr($i['content'], 0, 100) . '...'}}
                                        </p>
                                    @else
                                        <p>{{$i['content']}}</p>
                                    @endif
                                    {{-- <a href="{{ route('blog.show') }}" class="btn btn-primary px-5 rounded-pill">Read More</a> --}}
                                    <a href="{{ route('blog.show', $i->id) }}" class="btn btn-primary px-5 rounded-pill">Read More</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- Pagination Start -->
                @if($blog->hasPages())
                <div class="col-12">
                    <div class="pagination d-flex justify-content-center mt-5">
                        {{ $blog->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
                @endif
                <!-- Pagination End -->
                <!-- Blog List End -->
            </div>
        </div>
    </div>
    <!-- Blog End -->

    <!-- Newsletter Start -->
    <div class="container-fluid newsletter bg-primary py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-5 ps-lg-0 pt-5 pt-md-0 text-start wow fadeIn" data-wow-delay="0.3s">
                    <img class="img-fluid" src="/api/placeholder/600/400" alt="Newsletter Image">
                </div>
                <div class="col-md-7 pe-lg-0 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="text-white mb-4">Subscribe to Our Newsletter</h1>
                    <p class="text-white mb-4">Stay updated with the latest motorcycle models, financing options, and promotional offers. Subscribe to our newsletter today!</p>
                    <div class="position-relative w-100 mt-3">
                        <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Enter Your Email" style="height: 48px;">
                        <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="fa fa-paper-plane text-primary fs-4"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Newsletter End -->
@endsection

@section('footer')
    @include('fe.footer')
@endsection