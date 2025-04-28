<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Fitness Center</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --dark-blue: #0a1638;
            --red: #e01e3c;
            --white: #ffffff;
            --light-gray: #f3f3f3;
            --dark-gray: #333333;
        }

        body {
            background-color: var(--light-gray);
            font-family: 'Arial', sans-serif;
        }

        .top-bar {
            background-color: var(--dark-blue);
            color: var(--white);
            padding: 8px 0;
            font-size: 0.9rem;
        }

        .navbar {
            background-color: var(--dark-blue) !important;
            padding: 0;
        }

        .navbar-brand img {
            height: 50px;
        }

        .navbar .nav-link {
            color: var(--dark-gray) !important;
            padding: 20px 15px !important;
        }

        .navbar .nav-link:hover {
            background-color: var(--light-gray);
        }

        .navbar .nav-link.active {
            color: var(--red) !important;
            font-weight: bold;
        }

        .navbar-nav {
            background-color: var(--white);
            border-radius: 5px;
        }

        .btn-red {
            background-color: var(--red);
            color: var(--white);
            border: none;
        }

        .btn-red:hover {
            background-color: #c01a30;
            color: var(--white);
        }

        .search-btn {
            background-color: var(--red);
            color: var(--white);
            border: none;
            padding: 8px 12px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2); /* Tambahan background agar card lebih terlihat */
            border: none;
            box-shadow: none;
            margin-bottom: 20px;
        }

        .profile-header {
            padding-bottom: 20px;
            border-bottom: 1px solid var(--light-gray);
            margin-bottom: 30px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .section-title {
            color: var(--red);
            text-transform: uppercase;
            font-size: 18px;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .membership-card {
            background-color: var(--dark-blue);
            color: var(--white);
            border-radius: 10px;
            padding: 20px;
        }

        .membership-details span {
            color: var(--red);
            font-weight: bold;
        }

        .sidebar-nav .nav-link {
            color: var(--dark-gray);
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background-color: var(--red);
            color: var(--white);
        }

        .edit-profile-btn {
            position: static;
            margin-top: 15px;
        }

        .profile-container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('fe/img/bg-login.jpg')}}'); background-size: cover; background-position: center;">
    @auth('pelanggan') 
    <!-- Main Content -->
    <div class="container py-5">
        <div class="profile-container">
            <!-- Main Profile Content -->
                <div class="mb-3">
                    <a href="{{ route('/') }}" class="btn btn-outline-danger fw-bold">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            <div class="card">
                <div class="card-body">
                    <div class="profile-header">
                        <div class="profile-picture mb-3">
                            <img src="{{ asset('storage/' . Auth::guard('pelanggan')->user()->foto) }}" alt="Profile Picture">
                        </div>
                        <h3 class="mb-2 text-white">{{ Auth::guard('pelanggan')->user()->nama_pelanggan }}</h3>
                        <a href="{{route('profile.edit')}}" class="btn btn-red px-3 py-2 edit-profile-btn">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profile
                        </a>
                    </div>

                    <div class="mb-4">
                        <h5 class="section-title">Personal Information</h5>
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label text-white">Full Name</label>
                                    <input type="text" class="form-control" id="name" value="{{ Auth::guard('pelanggan')->user()->nama_pelanggan }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label text-white">Email Address</label>
                                    <input type="email" class="form-control" id="email" value="{{ Auth::guard('pelanggan')->user()->email }}" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label text-white">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" value="{{ Auth::guard('pelanggan')->user()->no_hp }}" disabled>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="mb-4">
                        <h5 class="section-title">Home Address</h5>
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="address" class="form-label text-white">Address</label>
                                    <input type="text" class="form-control" id="address" value="{{ Auth::guard('pelanggan')->user()->alamat1 }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="city" class="form-label text-white">City</label>
                                    <input type="text" class="form-control" id="city" value="{{ Auth::guard('pelanggan')->user()->kota1 }}" disabled>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="province" class="form-label text-white">Province/State</label>
                                    <input type="text" class="form-control" id="province" value="{{ Auth::guard('pelanggan')->user()->provinsi1 }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="postal" class="form-label text-white">Postal Code</label>
                                    <input type="text" class="form-control" id="postal" value="{{ Auth::guard('pelanggan')->user()->kode_pos1 }}" disabled>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
