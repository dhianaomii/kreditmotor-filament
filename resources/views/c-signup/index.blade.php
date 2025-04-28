@extends('fe.master')

<body style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('fe/img/bg-login.jpg')}}'); background-size: cover; background-position: center;">
    <div class="container py-5 min-vh-100 d-flex align-items-center" >
        <div class="row justify-content-center w-100 wow fadeInRight" data-wow-delay="0.4s">
            <div class="col-md-8 col-lg-6">
                <div class="form-section bg-dark p-4 p-md-5 rounded-3 shadow">
                    <h1 class="display-5 text-white mb-4 text-center">Register</h1>
                    
                    <form class="pt-3" action="/register" method="POST">
                        @csrf

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        {{-- Validasi Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    

                        <div class="d-grid mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0 bg-white text-black" 
                                       id="nama_pelanggan" name="nama_pelanggan" placeholder="Your Name" 
                                       value="{{ old('nama_pelanggan') }}" required>
                                <label for="nama_pelanggan" class="text-muted">Your Name</label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <div class="form-floating">
                                <input type="email" class="form-control border-0 bg-white text-black" 
                                       id="email" name="email" placeholder="Your Email" 
                                       value="{{ old('email') }}" required>
                                <label for="email" class="text-muted">Your Email</label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <div class="form-floating">
                                <input type="number" class="form-control border-0 bg-white text-black" 
                                       id="no_hp" name="no_hp" placeholder="Your Phone Number" 
                                       value="{{ old('no_hp') }}" required>
                                <label for="no_hp" class="text-muted">Your Phone Number</label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <div class="form-floating">
                                <input type="password" class="form-control border-0 bg-white text-black" 
                                       id="password" name="password" placeholder="Password" required>
                                <label for="password" class="text-muted">Password</label>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn btn-primary btn-lg py-3" type="submit">
                                Register
                            </button>
                        </div>

                        <div class="text-center text-white">
                            <p class="mb-0">Do you have an account? 
                                <a href="{{ route('login')}}" class="text-primary">Login here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>