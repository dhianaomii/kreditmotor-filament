<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Fitness Center</title>
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
            background-color: rgba(255, 255, 255, 0.2);
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

        .form-label {
            color: var(--white);
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('fe/img/bg-login.jpg') }}'); background-size: cover; background-position: center;">
    @auth('pelanggan')
    <!-- Main Content -->
    <div class="container py-5">
        <div class="profile-container">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('profile') }}" class="btn btn-outline-danger fw-bold">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
            <!-- Main Edit Profile Content -->
            <div class="card">
                <div class="card-body">
                    <div class="profile-header">
                        <div class="profile-picture mb-3">
                            <img src="{{ asset('storage/' . $pelanggan->foto) }}" alt="Profile Picture">
                        </div>
                        <h3 class="mb-2 text-white">{{ $pelanggan->nama_pelanggan }}</h3>
                    </div>

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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <h5 class="section-title">Personal Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nama_pelanggan" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}">
                                    @error('nama_pelanggan')
                                        <div class="invalid-feedback"></div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $pelanggan->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback"></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp) }}">
                                    @error('no_hp')
                                        <div class="invalid-feedback"></div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="foto" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                                    @error('foto')
                                        <div class="invalid-feedback"></div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="section-title">Home Address</h5>
                            <div id="address-container">
                                <!-- Alamat 1 (Wajib) -->
                                <div class="row mb-3 address-group" data-index="1">
                                    <div class="col-md-6">
                                        <label for="alamat1" class="form-label">Address 1</label>
                                        <input type="text" class="form-control @error('alamat1') is-invalid @enderror" id="alamat1" name="alamat1" value="{{ old('alamat1', $pelanggan->alamat1) }}">
                                        @error('alamat1')
                                            <div class="invalid-feedback"></div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kota1" class="form-label">City</label>
                                        <input type="text" class="form-control @error('kota1') is-invalid @enderror" id="kota1" name="kota1" value="{{ old('kota1', $pelanggan->kota1) }}">
                                        @error('kota1')
                                            <div class="invalid-feedback"></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="provinsi1" class="form-label">Province/State</label>
                                        <input type="text" class="form-control @error('provinsi1') is-invalid @enderror" id="provinsi1" name="provinsi1" value="{{ old('provinsi1', $pelanggan->provinsi1) }}">
                                        @error('provinsi1')
                                            <div class="invalid-feedback"></div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kode_pos1" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control @error('kode_pos1') is-invalid @enderror" id="kode_pos1" name="kode_pos1" value="{{ old('kode_pos1', $pelanggan->kode_pos1) }}">
                                        @error('kode_pos1')
                                            <div class="invalid-feedback"></div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Alamat 2 (Opsional, jika ada di database) -->
                                @if($pelanggan->alamat2)
                                    <div class="row mb-3 address-group" data-index="2">
                                        <div class="col-md-6">
                                            <label for="alamat2" class="form-label">Address 2</label>
                                            <input type="text" class="form-control @error('alamat2') is-invalid @enderror" id="alamat2" name="alamat2" value="{{ old('alamat2', $pelanggan->alamat2) }}">
                                            @error('alamat2')
                                                <div class="invalid-feedback"></div>
                                            @enderror
                                        </div>
                                        <div class="col-md-5">
                                            <label for="kota2" class="form-label">City</label>
                                            <input type="text" class="form-control @error('kota2') is-invalid @enderror" id="kota2" name="kota2" value="{{ old('kota2', $pelanggan->kota2) }}">
                                            @error('kota2')
                                                <div class="invalid-feedback"></div>
                                            @enderror
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-address-btn"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="provinsi2" class="form-label">Province/State</label>
                                            <input type="text" class="form-control @error('provinsi2') is-invalid @enderror" id="provinsi2" name="provinsi2" value="{{ old('provinsi2', $pelanggan->provinsi2) }}">
                                            @error('provinsi2')
                                                <div class="invalid-feedback"></div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="kode_pos2" class="form-label">Postal Code</label>
                                            <input type="text" class="form-control @error('kode_pos2') is-invalid @enderror" id="kode_pos2" name="kode_pos2" value="{{ old('kode_pos2', $pelanggan->kode_pos2) }}">
                                            @error('kode_pos2')
                                                <div class="invalid-feedback"></div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                <!-- Alamat 3 (Opsional, jika ada di database) -->
                                @if($pelanggan->alamat3)
                                    <div class="row mb-3 address-group" data-index="3">
                                        <div class="col-md-6">
                                            <label for="alamat3" class="form-label">Address 3</label>
                                            <input type="text" class="form-control @error('alamat3') is-invalid @enderror" id="alamat3" name="alamat3" value="{{ old('alamat3', $pelanggan->alamat3) }}">
                                            @error('alamat3')
                                                <div class="invalid-feedback"></div>
                                            @enderror
                                        </div>
                                        <div class="col-md-5">
                                            <label for="kota3" class="form-label">City</label>
                                            <input type="text" class="form-control @error('kota3') is-invalid @enderror" id="kota3" name="kota3" value="{{ old('kota3', $pelanggan->kota3) }}">
                                            @error('kota3')
                                                <div class="invalid-feedback"></div>
                                            @enderror
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-address-btn"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="provinsi3" class="form-label">Province/State</label>
                                            <input type="text" class="form-control @error('provinsi3') is-invalid @enderror" id="provinsi3" name="provinsi3" value="{{ old('provinsi3', $pelanggan->provinsi3) }}">
                                            @error('provinsi3')
                                                <div class="invalid-feedback"></div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="kode_pos3" class="form-label">Postal Code</label>
                                            <input type="text" class="form-control @error('kode_pos3') is-invalid @enderror" id="kode_pos3" name="kode_pos3" value="{{ old('kode_pos3', $pelanggan->kode_pos3) }}">
                                            @error('kode_pos3')
                                                <div class="invalid-feedback"></div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Tombol Tambah Alamat -->
                            <div class="mb-3">
                                <button type="button" id="add-address-btn" class="btn btn-outline-primary" {{ ($pelanggan->alamat3) ? 'disabled' : '' }}>
                                    <i class="bi bi-plus-circle me-2"></i>Tambah Alamat
                                </button>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-red px-4 py-2">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript untuk menambah dan menghapus alamat -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addressContainer = document.getElementById('address-container');
            const addAddressBtn = document.getElementById('add-address-btn');
            let addressCount = document.querySelectorAll('.address-group').length;

            // Fungsi untuk menambah field alamat baru
            addAddressBtn.addEventListener('click', function () {
                if (addressCount >= 3) {
                    alert('Maksimal 3 alamat diperbolehkan.');
                    return;
                }

                addressCount++;
                const newAddressIndex = addressCount;

                const addressHtml = `
                    <div class="row mb-3 address-group" data-index="${newAddressIndex}">
                        <div class="col-md-6">
                            <label for="alamat${newAddressIndex}" class="form-label">Address ${newAddressIndex}</label>
                            <input type="text" class="form-control" id="alamat${newAddressIndex}" name="alamat${newAddressIndex}" value="">
                        </div>
                        <div class="col-md-5">
                            <label for="kota${newAddressIndex}" class="form-label">City</label>
                            <input type="text" class="form-control" id="kota${newAddressIndex}" name="kota${newAddressIndex}" value="">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-address-btn"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="provinsi${newAddressIndex}" class="form-label">Province/State</label>
                            <input type="text" class="form-control" id="provinsi${newAddressIndex}" name="provinsi${newAddressIndex}" value="">
                        </div>
                        <div class="col-md-6">
                            <label for="kode_pos${newAddressIndex}" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="kode_pos${newAddressIndex}" name="kode_pos${newAddressIndex}" value="">
                        </div>
                    </div>
                `;

                addressContainer.insertAdjacentHTML('beforeend', addressHtml);

                // Perbarui tombol "Tambah Alamat"
                if (addressCount >= 3) {
                    addAddressBtn.disabled = true;
                }
            });

            // Fungsi untuk menghapus field alamat
            addressContainer.addEventListener('click', function (e) {
                if (e.target.closest('.remove-address-btn')) {
                    const addressGroup = e.target.closest('.address-group');
                    const nextRow = addressGroup.nextElementSibling; // Baris provinsi dan kode pos
                    addressGroup.remove();
                    nextRow.remove();
                    addressCount--;

                    // Aktifkan kembali tombol "Tambah Alamat" jika addressCount < 3
                    if (addressCount < 3) {
                        addAddressBtn.disabled = false;
                    }
                }
            });
        });
    </script>
</body>
</html>