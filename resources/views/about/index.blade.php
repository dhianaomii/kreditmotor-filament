@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">About Us</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-primary">About Us</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="display-5 mb-4">Your Trusted Partner for Motorcycle Financing</h1>
                    <p class="mb-4">We understand that owning a motorcycle represents freedom and independence. Our mission is to make motorcycle ownership accessible to everyone through flexible and affordable credit solutions.</p>
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-check-lg fs-2 text-white"></i>
                        </div>
                        <div class="ps-3">
                            <h5 class="mb-0">Fast Approval Process</h5>
                            <p class="mb-0">Get approved within 24 hours</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-wallet fs-2 text-white"></i>
                        </div>
                        <div class="ps-3">
                            <h5 class="mb-0">Competitive Interest Rates</h5>
                            <p class="mb-0">Starting from 0.99% per month</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-shield-check fs-2 text-white"></i>
                        </div>
                        <div class="ps-3">
                            <h5 class="mb-0">Flexible Payment Terms</h5>
                            <p class="mb-0">Choose from 12 to 60 months</p>
                        </div>
                    </div>
                    <a href="contact.html" class="btn btn-primary py-3 px-5 mt-3">Contact Us For Consultation</a>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="position-relative rounded overflow-hidden h-100" style="min-height: 400px;">
                        <img class="position-absolute w-100 h-100" src="{{ asset ('fe/img/motor1.jpg')}}" alt="Motorcycle Financing" style="object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Features Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <h1 class="display-5 mb-4">Why Choose Our Motorcycle Credit</h1>
                <p class="mb-0">With over 15 years of experience in the motorcycle financing industry, we've helped thousands of customers ride their dream motorcycles.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item bg-white p-5 text-center">
                        <i class="bi bi-calculator-fill fs-1 text-primary mb-4"></i>
                        <h5 class="mb-3">Transparent Pricing</h5>
                        <p class="mb-0">No hidden fees or charges. What you see is what you get with our motorcycle financing plans.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item bg-white p-5 text-center">
                        <i class="bi bi-file-earmark-text fs-1 text-primary mb-4"></i>
                        <h5 class="mb-3">Minimal Documentation</h5>
                        <p class="mb-0">We've simplified the application process to require only essential documents.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item bg-white p-5 text-center">
                        <i class="bi bi-headset fs-1 text-primary mb-4"></i>
                        <h5 class="mb-3">Dedicated Support</h5>
                        <p class="mb-0">Our customer service team is available 7 days a week to answer all your questions.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item bg-white p-5 text-center">
                        <i class="bi bi-brightness-high fs-1 text-primary mb-4"></i>
                        <h5 class="mb-3">All Brands Coverage</h5>
                        <p class="mb-0">We provide financing options for all major motorcycle brands and models.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item bg-white p-5 text-center">
                        <i class="bi bi-currency-exchange fs-1 text-primary mb-4"></i>
                        <h5 class="mb-3">Early Repayment Options</h5>
                        <p class="mb-0">Pay off your loan early with no additional penalties or hidden charges.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item bg-white p-5 text-center">
                        <i class="bi bi-geo-alt fs-1 text-primary mb-4"></i>
                        <h5 class="mb-3">Nationwide Service</h5>
                        <p class="mb-0">With branches across the country, we're always close to you when you need us.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->

    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 700px;">
                <h1 class="display-5 mb-4">Our Leadership Team</h1>
                <p class="mb-0">Meet the experienced professionals who make your motorcycle financing journey smooth and hassle-free.</p>
            </div>
            <div class="row g-5">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item bg-white text-center rounded overflow-hidden pt-4">
                        <img class="img-fluid w-50 rounded-circle" style="height: 200px; object-fit: cover;" src="{{ asset('fe/img/orang1.jpg') }}" alt="Team Member">
                        <div class="team-content">
                            <h5 class="mb-0">John Anderson</h5>
                            <p class="text-primary">Chief Executive Officer</p>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-facebook"></i></a>
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-twitter"></i></a>
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item bg-white text-center rounded overflow-hidden pt-4">
                        <img class="img-fluid w-50 rounded-circle" style="height: 200px; object-fit: cover;" src="{{ asset('fe/img/orang2.jpg') }}" alt="Team Member">
                        <div class="team-content">
                            <h5 class="mb-0">Sarah Johnson</h5>
                            <p class="text-primary">Operations Director</p>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-facebook"></i></a>
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-twitter"></i></a>
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="team-item bg-white text-center rounded overflow-hidden pt-4">
                        <img class="img-fluid w-50 rounded-circle" style="height: 200px; object-fit: cover;" src="{{ asset('fe/img/orang3.jpg') }}" alt="Team Member">
                        <div class="team-content">
                            <h5 class="mb-0">Michael Rodriguez</h5>
                            <p class="text-primary">Finance Manager</p>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-facebook"></i></a>
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-twitter"></i></a>
                                <a class="btn btn-square btn-primary m-1" href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->

    <!-- Call To Action Start -->
    <div class="container-fluid call-to-action bg-primary bg-call-to-action py-5">
        <div class="container py-5">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6 text-center wow fadeInUp" data-wow-delay="0.3s">
                    <h1 class="display-5 mb-4 text-white">Ready to Ride Your Dream Motorcycle?</h1>
                    <p class="mb-4 text-white">Apply for motorcycle financing today and get on the road with your dream bike. Our team is standing by to assist you.</p>
                    <a href="apply.html" class="btn btn-light py-3 px-5">Apply Now</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Call To Action End -->
@endsection

@section('footer')
    @include('fe.footer')
@endsection