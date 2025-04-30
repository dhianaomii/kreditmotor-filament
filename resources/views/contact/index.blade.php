@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
   <!-- Header Start -->
   <div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Contact Us</h4>
        {{-- <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-primary">Contact</li>
        </ol>     --}}
    </div>
</div>
<!-- Header End -->

<!-- Contact Start -->
<div class="container-fluid contact py-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                <div class="mb-4">
                    <h4 class="text-primary">Contact Us</h4>
                    <h1 class="display-4 mb-4">Contact With Team Of Experts</h1>
                    <p class="mb-4">The contact form is currently inactive. Get a functional and working contact form with Ajax & PHP in a few minutes. Just copy and paste the files, add a little code and you're done. <a class="text-primary fw-bold" href="https://htmlcodex.com/contact-form">Download Now</a>.
                    </p>
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="bg-white d-flex p-3">
                                <i class="fas fa-map-marker-alt fa-2x text-primary me-2"></i>
                                <div>
                                    <h4>Address</h4>
                                    <p class="mb-0">SMKN 1 Cibinong</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="bg-white d-flex p-3">
                                <i class="fas fa-envelope fa-2x text-primary me-2"></i>
                                <div>
                                    <h4>Mail Us</h4>
                                    <p class="mb-0">kreditayesh@example.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="bg-white d-flex p-3">
                                <i class="fa fa-phone-alt fa-2x text-primary me-2"></i>
                                <div>
                                    <h4>Telephone</h4>
                                    <p class="mb-0">(+62) 812 8119 0736</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="bg-white d-flex p-3">
                                <i class="fab fa-firefox-browser fa-2x text-primary me-2"></i>
                                <div>
                                    <h4>Yoursite@ex.com</h4>
                                    <p class="mb-0">(+012) 3456 7890</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex ms-2 mb-5">
                    <a class="btn btn-dark py-2 px-3 px-sm-4 me-2" href="https://www.facebook.com"> <span>facebook</span> <i class="fas fa-chevron-circle-right"></i></a>
                    <a class="btn btn-dark py-2 px-3 px-sm-4 mx-2" href="https://www.x.com""> <span>twitter</span> <i class="fas fa-chevron-circle-right"></i></a>
                    <a class="btn btn-dark py-2 px-3 px-sm-4 ms-2" href="https://www.instagram.com"> <span>instagram</span> <i class="fas fa-chevron-circle-right"></i></a>
                </div>
                {{-- <div class="contact-banner">
                    <div class="row g-0">
                        <div class="col-9">
                            <div class="p-4 pe-0">
                                <h4 class="display-5 mb-0">Want To Join Our Talanded Team</h4>
                                <div class="d-flex align-items-center">
                                    <a href="index.html" class="h5 mb-0 me-3">visit our website</a>
                                    <a class="text-primary py-3" href="https://www.youtube.com/embed/DWRcNpR6Kdc"><i class="fas fa-play-circle me-2"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">
                <div class="form-section bg-dark p-5 h-100">
                    <h1 class="display-4 text-white mb-4">Get In touch</h1>
                    <form>
                        <div class="row g-4">
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-floating form-section-col">
                                    <input type="text" class="form-control border-0" id="name" placeholder="Your Name">
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-floating form-section-col">
                                    <input type="email" class="form-control border-0" id="email" placeholder="Your Email">
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-floating form-section-col">
                                    <input type="phone" class="form-control border-0" id="phone" placeholder="Phone">
                                    <label for="phone">Your Phone</label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-floating form-section-col">
                                    <input type="text" class="form-control border-0" id="project" placeholder="Project">
                                    <label for="project">Your Project</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating form-section-col">
                                    <input type="text" class="form-control border-0" id="subject" placeholder="Subject">
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control border-0" placeholder="Leave a message here" id="message" style="height: 160px;"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="#" id="flexCheck">
                                    <label class="form-check-label" for="flexCheck">I agree with the site privacy policy</label>
                                  </div>
                            </div>
                            <div class="col-12">
                                <div class="form-section-col">
                                    <button class="btn-primary w-100 py-3 px-5">Send Message</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 wow fadeInUp" data-wow-delay="0.2s">
                <div class="h-100 overflow-hidden">
                    <iframe class="w-100" style="height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.997759836978!2d106.80546671057158!3d-6.521963993443334!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c3db9bbedcc3%3A0x1f5280e86053b1e9!2sSMK%20NEGERI%201%20Cibinong!5e0!3m2!1sid!2sid!4v1746002652917!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" 
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->
@endsection

@section('footer')
    @include('fe.footer')
@endsection