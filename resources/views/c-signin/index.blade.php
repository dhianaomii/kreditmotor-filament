@extends('fe.master')

<body style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('fe/img/bg-login.jpg')}}'); background-size: cover; background-position: center;">
    <div class="container py-5 min-vh-100 d-flex align-items-center" >
        <div class="row justify-content-center w-100 wow fadeInRight" data-wow-delay="0.4s">
            <div class="col-md-8 col-lg-6">
                <div class="form-section bg-dark p-4 p-md-5 rounded-3 shadow">
                    <h1 class="display-5 text-white mb-4 text-center">Login</h1>
                    
                    <form class="pt-3" action="/login" method="POST">
                        @csrf

                        {{-- @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif --}}

                        <div class="d-grid mb-4">
                            <div class="form-floating">
                                <input type="email" class="form-control border-0 bg-white text-black" 
                                       id="email" name="email" placeholder="Your Email" 
                                       value="{{ old('email') }}" required>
                                <label for="email" class="text-muted">Your Email</label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <div class="form-floating position-relative">
                                <input type="password" class="form-control border-0 bg-white text-black" 
                                       id="password" name="password" placeholder="Password" required>
                                <label for="password" class="text-muted">Password</label>
                                <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted" 
                                        onclick="togglePassword()">
                                    <i class="bi bi-eye-slash fs-5 p-4" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button class="btn btn-primary btn-lg py-3" type="submit">
                                Login
                            </button>
                        </div>

                        <div class="text-center text-white">
                            <p class="mb-0">Don't have an account? 
                                <a href="{{ route('register.form')}}" class="text-primary">Register here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }
    </script>
</body>