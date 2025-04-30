@extends('fe.master') 

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
    <!-- Carousel Start -->
    <div class="header-carousel owl-carousel overflow-hidden bg-dark">
        <div class="header-carousel-item hero-section">
            <div class="hero-bg-half-1"></div>
            <div class="carousel-caption">
                <div class="container">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7 animated fadeInLeft">
                            <div class="text-sm-center text-md-start">
                                <h4 class="text-primary text-uppercase fw-bold mb-4">Welcome to our Motorbike Credit Website</h4>
                                <h1 class="display-1 text-white mb-4">The best support, the best deal, the best journey to your dream motor!</h1>
                                <p class="mb-5 fs-5">Getting your dream motorcycle has never been easier. Apply for credit online, get approved quickly, and ride away on your new bike with affordable monthly payments.</p>
                                <div class="d-flex justify-content-center justify-content-md-start flex-shrink-0 mb-4">
                                    <a class="btn btn-primary py-3 px-4 px-md-5 ms-2" href="{{route('product')}}"><span>Apply Now</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Benefits Start -->
    <div class="container-fluid goal pt-5">
        <div class="container pt-5">
            <div class="row g-5">
                <div class="col-lg-8 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="goal-content">
                        <h4 class="text-primary">Our Benefits</h4>
                        <h1 class="display-4 mb-4">Why Choose Our Motorcycle Credit Service?</h1>
                        <div class="goal-item d-flex p-4">
                            <div class="d-flex me-4">
                                <div class="bg-primary d-inline p-3" style="width: 80px; height: 80px;">
                                    <img src="{{ asset ('fe/img/icon-1.png')}}" class="img-fluid" alt="">
                                </div>
                            </div>
                            <div>
                                <h4>Fast Approval Process</h4>
                                <p class="text-white mb-0">Get your credit application approved within 24 hours with minimal requirements and documentation.</p>
                            </div>
                        </div>
                        <div class="goal-item d-flex p-4 mb-4">
                            <div class="d-flex me-4">
                                <div class="bg-primary d-inline p-3" style="width: 80px; height: 80px;">
                                    <img src="{{ asset ('fe/img/icon-6.png')}}" class="img-fluid" alt="">
                                </div>
                            </div>
                            <div>
                                <h4>Low Down Payment</h4>
                                <p class="text-white mb-0">Start with as little as 10% down payment and flexible payment terms that fit your budget.</p>
                            </div>
                        </div>
                        <div class="ms-1">
                            <a href="{{ route('about')}}" class="btn btn-primary py-3 px-5 ms-2"> <span>Learn More</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Benefits End -->

    <!-- Products Start -->
    <div class="container-fluid feature bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Our Offerings</h4>
                <h1 class="display-4 mb-4">Popular Motorcycle Models</h1>
                <p class="mb-0">Discover our wide selection of motorcycles with flexible credit options. We offer models for every rider, from beginners to experienced enthusiasts, all with competitive financing plans.
                </p>
            </div>
            
            <div class="row g-4">
                @foreach ($data as $i)
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="feature-item">
                        <div class="feature-img" style="height: 250px; overflow: hidden;">
                            <img src="{{ asset ('storage/'.$i->foto1)}}" class="img-fluid w-100 h-100" style="object-fit: cover; object-position: center;" alt="">
                        </div>
                        <div class="feature-content p-4">
                            <h4 class="mb-3">{{ $i->nama_motor}}</h4>
                            <p class="mb-2">{{ $i->deskripsi_motor}}</p>
                            @if($i->stok > 10)
                                <span class="mb-2 badge bg-success">{{ $i->stok }} Available</span>
                            @elseif($i->stok > 0)
                                <span class="mb-2 badge bg-warning text-dark">{{ $i->stok }} Limited Stock</span>
                            @else
                                <span class="mb-2 badge bg-danger">Out of Stock</span>
                            @endif
                            <h5 class="mb-3">Rp {{ number_format($i->harga_jual)}}</h5>
                            <a href="{{route('product.show', $i->id)}}" class="btn btn-primary py-2 px-4"> <span>View Motors</span></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5 wow fadeInUp" data-wow-delay="0.3s">
                <a href="{{route('product')}}" class="btn btn-primary btn-lg py-3 px-5"><span>View All Products</span></a>
            </div>
        </div>
    </div>
    <!-- Products End -->

    <!-- Call to Action Start -->
    <div class="container-fluid explore py-5 wow zoomIn" data-wow-delay="0.2s">
        <div class="container py-5 text-center">
            <h1 class="display-1 text-white mb-0">Ready to Ride Your Dream Bike?</h1>
            <a class="btn btn-primary py-3 px-4 px-md-5 me-2" href="{{route('product')}}"><i class="fas fa-motorcycle me-2"></i> <span>Apply For Credit Now</span></a>
        </div>
    </div>
    <!-- Call to Action End -->

    <!-- Blog Start -->
    <div class="container-fluid blog py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Our Blogs</h4>
                <h1 class="display-4 mb-4">Latest Motorcycle News & Tips</h1>
                <p class="mb-0">Stay updated with the latest motorcycle trends, maintenance tips, and credit options to make the most informed decisions for your motorcycle purchase.
                </p>
            </div>
            <div class="blog-carousel owl-carousel">
                <div class="blog-item wow fadeInUp" data-wow-delay="0.2s">
                    <div class="blog-img p-4 pb-0">
                        <a href="#">
                            <img src="{{ asset ('fe/img/feature-4.jpg')}}" class="img-fluid w-100" alt="">
                        </a>
                    </div>
                    <div class="blog-content p-4">
                        <div class="blog-comment d-flex justify-content-between py-2 px-3 mb-4">
                            <div class="small"><span class="fa fa-user text-primary me-2"></span> Admin</div>
                            <div class="small"><span class="fa fa-calendar text-primary me-2"></span> 15 Apr 2025</div>
                        </div>
                        <a href="#" class="h4 d-inline-block mb-3">How to Choose Your First Motorcycle</a>
                        <p class="mb-3">Tips for new riders to select the perfect motorcycle that matches their skill level, budget, and riding style.</p>
                        <a href="#" class="btn btn-dark py-2 px-4 ms-2"> <span class="me-2">Read More</span>  <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="blog-item wow fadeInUp" data-wow-delay="0.4s">
                    <div class="blog-img p-4 pb-0">
                        <a href="#">
                            <img src="{{ asset ('fe/img/feature-3.jpg')}}" class="img-fluid w-100" alt="">
                        </a>
                    </div>
                    <div class="blog-content p-4">
                        <div class="blog-comment d-flex justify-content-between py-2 px-3 mb-4">
                            <div class="small"><span class="fa fa-user text-primary me-2"></span> Admin</div>
                            <div class="small"><span class="fa fa-calendar text-primary me-2"></span> 10 Apr 2025</div>
                        </div>
                        <a href="#" class="h4 d-inline-block mb-3">Understanding Motorcycle Financing</a>
                        <p class="mb-3">A comprehensive guide to motorcycle credit terms, interest rates, and how to get the best deal for your budget.</p>
                        <a href="#" class="btn btn-dark py-2 px-4 ms-2"> <span class="me-2">Read More</span>  <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="blog-item wow fadeInUp" data-wow-delay="0.6s">
                    <div class="blog-img p-4 pb-0">
                        <a href="#">
                            <img src="{{ asset ('fe/img/feature-2.jpg')}}" class="img-fluid w-100" alt="">
                        </a>
                    </div>
                    <div class="blog-content p-4">
                        <div class="blog-comment d-flex justify-content-between py-2 px-3 mb-4">
                            <div class="small"><span class="fa fa-user text-primary me-2"></span> Admin</div>
                            <div class="small"><span class="fa fa-calendar text-primary me-2"></span> 5 Apr 2025</div>
                        </div>
                        <a href="#" class="h4 d-inline-block mb-3">Essential Motorcycle Maintenance</a>
                        <p class="mb-3">Regular maintenance tips to keep your motorcycle running smoothly and maintain its value over time.</p>
                        <a href="#" class="btn btn-dark py-2 px-4 ms-2"> <span class="me-2">Read More</span>  <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="blog-img p-4 pb-0">
                        <a href="#">
                            <img src="{{ asset ('fe/img/feature-1.jpg')}}" class="img-fluid w-100" alt="">
                        </a>
                    </div>
                    <div class="blog-content p-4">
                        <div class="blog-comment d-flex justify-content-between py-2 px-3 mb-4">
                            <div class="small"><span class="fa fa-user text-primary me-2"></span> Admin</div>
                            <div class="small"><span class="fa fa-calendar text-primary me-2"></span> 1 Apr 2025</div>
                        </div>
                        <a href="#" class="h4 d-inline-block mb-3">Best Motorcycles for Beginners</a>
                        <p class="mb-3">Our top recommendations for new riders looking for safe, manageable, and affordable first motorcycles.</p>
                        <a href="#" class="btn btn-dark py-2 px-4 ms-2"> <span class="me-2">Read More</span>  <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Blog End -->

    <!-- Testimonial Start -->
    <div class="container-fluid testimonial bg-dark py-5" style="margin-bottom: 90px;">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Success Stories</h4>
                <h1 class="display-4 text-white">Riders Who Trusted Our Credit Solutions</h1>
            </div>
            <div class="testimonial-carousel owl-carousel wow fadeInUp" data-wow-delay="0.2s">
                <div class="testimonial-item mx-auto" style="max-width: 900px;">
                    <span class="fa fa-quote-left fa-3x quote-icon"></span>
                    <div class="testimonial-img mb-4">
                        <img src="{{ asset ('fe/img/testimonial-1.jpg')}}" class="img-fluid" alt="Image">
                    </div>
                    <p class="fs-4 text-white mb-4">The approval process for my motorcycle loan was incredibly fast. Within 24 hours, I received the green light and was able to pick up my dream Kawasaki Ninja the very next day. The interest rate was much lower than other lenders offered, saving me thousands over the loan term.
                    </p>
                    <div class="d-block">
                        <h4 class="text-white">Ahmad Firdaus</h4>
                        <p class="m-0 pb-3">IT Professional</p>
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item mx-auto" style="max-width: 900px;">
                    <span class="fa fa-quote-left fa-3x quote-icon"></span>
                    <div class="testimonial-img mb-4">
                        <img src="{{ asset ('fe/img/testimonial-2.jpg')}}" class="img-fluid" alt="Image">
                    </div>
                    <p class="fs-4 text-white mb-4">As a first-time motorcycle buyer, I was nervous about finding the right financing option. The staff guided me through every step and helped me understand all the terms. Their flexible payment plans allowed me to buy a Honda PCX that fit perfectly within my monthly budget.
                    </p>
                    <div class="d-block">
                        <h4 class="text-white">Siti Rahayu</h4>
                        <p class="m-0 pb-3">College Student</p>
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item mx-auto" style="max-width: 900px;">
                    <span class="fa fa-quote-left fa-3x quote-icon"></span>
                    <div class="testimonial-img mb-4">
                        <img src="{{ asset ('fe/img/testimonial-3.jpg')}}" class="img-fluid" alt="Image">
                    </div>
                    <p class="fs-4 text-white mb-4">When my credit application was rejected by two other lenders, I didn't think I'd be able to get the motorcycle I needed for my delivery business. Their specialized credit program for entrepreneurs helped me secure financing for a Yamaha NMAX, which has been essential for growing my small business.
                    </p>
                    <div class="d-block">
                        <h4 class="text-white">Budi Santoso</h4>
                        <p class="m-0 pb-3">Small Business Owner</p>
                        <div class="d-flex">
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                            <i class="fas fa-star text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
@endsection

@section('footer')
    @include('fe.footer')
@endsection
