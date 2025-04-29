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
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active text-primary">Blog</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

    <!-- Blog Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="row g-4">
                <!-- Blog Filter Start -->
                <div class="col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded p-4 mb-4">
                        <h4 class="mb-3">Search</h4>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Keyword">
                            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                    <div class="bg-light rounded p-4 mb-4">
                        <h4 class="mb-3">Categories</h4>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="h5 mb-3" href="#"><i class="bi bi-arrow-right me-2"></i>Motorcycle Financing</a>
                            <a class="h5 mb-3" href="#"><i class="bi bi-arrow-right me-2"></i>Motorcycle Maintenance</a>
                            <a class="h5 mb-3" href="#"><i class="bi bi-arrow-right me-2"></i>Latest Models</a>
                            <a class="h5 mb-3" href="#"><i class="bi bi-arrow-right me-2"></i>Riding Tips</a>
                            <a class="h5 mb" href="#"><i class="bi bi-arrow-right me-2"></i>Market Updates</a>
                        </div>
                    </div>
                    <div class="bg-light rounded p-4 mb-4">
                        <h4 class="mb-3">Recent Posts</h4>
                        <div class="d-flex mb-3">
                            <img class="img-fluid rounded" src="/api/placeholder/80/80" alt="Recent Post" style="object-fit: cover;">
                            <a href="" class="h5 d-flex align-items-center ms-3">How to Choose the Right Motorcycle for Beginners</a>
                        </div>
                        <div class="d-flex mb-3">
                            <img class="img-fluid rounded" src="/api/placeholder/80/80" alt="Recent Post" style="object-fit: cover;">
                            <a href="" class="h5 d-flex align-items-center ms-3">Understanding Interest Rates for Motorcycle Loans</a>
                        </div>
                        <div class="d-flex">
                            <img class="img-fluid rounded" src="/api/placeholder/80/80" alt="Recent Post" style="object-fit: cover;">
                            <a href="" class="h5 d-flex align-items-center ms-3">5 Tips to Maintain Your Motorcycle in Rainy Season</a>
                        </div>
                    </div>
                </div>
                <!-- Blog Filter End -->
                
                <!-- Blog List Start -->
                <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="blog-item">
                                <div class="rounded overflow-hidden">
                                    <img class="img-fluid w-100" src="/api/placeholder/350/200" alt="Blog Image">
                                    <div class="blog-overlay">
                                        <a class="btn btn-square btn-primary" href="blog-detail.html"><i class="bi bi-link"></i></a>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta d-flex justify-content-between py-3">
                                        <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>Admin</h6>
                                        <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>28 Apr 2025</h6>
                                    </div>
                                    <h5 class="mb-3">5 Strategies to Improve Your Credit Score Before Applying for a Motorcycle Loan</h5>
                                    <p class="mb-4">Preparing your finances before applying for motorcycle financing can significantly increase your chances of approval and help you secure a better interest rate...</p>
                                    <a href="blog-detail.html" class="btn btn-primary px-5 rounded-pill">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-item">
                                <div class="rounded overflow-hidden">
                                    <img class="img-fluid w-100" src="/api/placeholder/350/200" alt="Blog Image">
                                    <div class="blog-overlay">
                                        <a class="btn btn-square btn-primary" href="blog-detail.html"><i class="bi bi-link"></i></a>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta d-flex justify-content-between py-3">
                                        <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>Admin</h6>
                                        <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>25 Apr 2025</h6>
                                    </div>
                                    <h5 class="mb-3">The New 2025 Models: Which Motorcycles Offer the Best Value for Money</h5>
                                    <p class="mb-4">The latest motorcycle models have arrived, and we're reviewing which ones offer the best features, performance, and overall value for your investment...</p>
                                    <a href="blog-detail.html" class="btn btn-primary px-5 rounded-pill">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-item">
                                <div class="rounded overflow-hidden">
                                    <img class="img-fluid w-100" src="/api/placeholder/350/200" alt="Blog Image">
                                    <div class="blog-overlay">
                                        <a class="btn btn-square btn-primary" href="blog-detail.html"><i class="bi bi-link"></i></a>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta d-flex justify-content-between py-3">
                                        <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>Admin</h6>
                                        <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>20 Apr 2025</h6>
                                    </div>
                                    <h5 class="mb-3">Understanding Down Payments: How Much Should You Put Down on a Motorcycle?</h5>
                                    <p class="mb-4">The size of your down payment can significantly impact your monthly payments and total interest paid. We break down the ideal down payment amounts for different scenarios...</p>
                                    <a href="blog-detail.html" class="btn btn-primary px-5 rounded-pill">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-item">
                                <div class="rounded overflow-hidden">
                                    <img class="img-fluid w-100" src="/api/placeholder/350/200" alt="Blog Image">
                                    <div class="blog-overlay">
                                        <a class="btn btn-square btn-primary" href="blog-detail.html"><i class="bi bi-link"></i></a>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta d-flex justify-content-between py-3">
                                        <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>Admin</h6>
                                        <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>18 Apr 2025</h6>
                                    </div>
                                    <h5 class="mb-3">Essential Motorcycle Insurance: What Coverage Do You Really Need?</h5>
                                    <p class="mb-4">With so many insurance options available, it can be challenging to determine which coverage is necessary and which is optional. Our guide helps you make informed decisions...</p>
                                    <a href="blog-detail.html" class="btn btn-primary px-5 rounded-pill">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-item">
                                <div class="rounded overflow-hidden">
                                    <img class="img-fluid w-100" src="/api/placeholder/350/200" alt="Blog Image">
                                    <div class="blog-overlay">
                                        <a class="btn btn-square btn-primary" href="blog-detail.html"><i class="bi bi-link"></i></a>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta d-flex justify-content-between py-3">
                                        <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>Admin</h6>
                                        <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>15 Apr 2025</h6>
                                    </div>
                                    <h5 class="mb-3">Electric Motorcycles: Are They Worth the Investment?</h5>
                                    <p class="mb-4">Electric motorcycles are gaining popularity, but are they a good financial decision? We analyze the costs, benefits, and financing options for electric two-wheelers...</p>
                                    <a href="blog-detail.html" class="btn btn-primary px-5 rounded-pill">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-item">
                                <div class="rounded overflow-hidden">
                                    <img class="img-fluid w-100" src="/api/placeholder/350/200" alt="Blog Image">
                                    <div class="blog-overlay">
                                        <a class="btn btn-square btn-primary" href="blog-detail.html"><i class="bi bi-link"></i></a>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta d-flex justify-content-between py-3">
                                        <h6 class="mb-0"><i class="bi bi-person text-primary me-2"></i>Admin</h6>
                                        <h6 class="mb-0"><i class="bi bi-calendar text-primary me-2"></i>12 Apr 2025</h6>
                                    </div>
                                    <h5 class="mb-3">Motorcycle Maintenance: How to Protect Your Investment</h5>
                                    <p class="mb-4">Regular maintenance is crucial for preserving your motorcycle's value and performance. Learn the essential maintenance tasks that every rider should perform...</p>
                                    <a href="blog-detail.html" class="btn btn-primary px-5 rounded-pill">Read More</a>
                                </div>
                            </div>
                        </div>
                        <!-- Pagination Start -->
                        <div class="col-12">
                            <div class="pagination d-flex justify-content-center mt-5">
                                <a href="#" class="rounded mx-2 active">1</a>
                                <a href="#" class="rounded mx-2">2</a>
                                <a href="#" class="rounded mx-2">3</a>
                                <a href="#" class="rounded mx-2"><i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                        <!-- Pagination End -->
                    </div>
                </div>
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