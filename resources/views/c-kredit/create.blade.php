@extends('fe.master')

<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Pembayaran Kredit Motor</h4>  
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-dark-blue">Pembayaran Kredit Motor</h2>
        <a href="{{ route('pengajuan') }}" class="btn btn-outline-red">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Summary Card - Only show if pengajuan exists -->
    @if($pengajuan)
    <div class="card mb-5">
        <div class="product-title-bar">
            <h4 class="mb-0 text-white">
                <i class="bi bi-info-circle me-2"></i>
                Ringkasan Pengajuan
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Detail Pembayaran</h5>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Harga Cash:</span>
                        <span>IDR {{ number_format($pengajuan->harga_cash, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Harga Kredit:</span>
                        <span>IDR {{ number_format($pengajuan->harga_kredit, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>DP:</span>
                        <span>IDR {{ number_format($pengajuan->dp, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Total Biaya Asuransi:</span>
                        <span>IDR {{ number_format($pengajuan->biaya_asuransi_perbulan * $pengajuan->JenisCicilan->lama_cicilan, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between fw-bold">
                        <span>Total yang Harus Dibayar:</span>
                        <span class="text-danger">IDR {{ number_format($pengajuan->dp + ($pengajuan->cicilan_perbulan * $pengajuan->JenisCicilan->lama_cicilan), 2, ',', '.') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Info Cicilan</h5>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Jangka Waktu:</span>
                        <span>{{ $pengajuan->JenisCicilan->lama_cicilan ?? '-' }} bulan</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Cicilan per Bulan:</span>
                        <span>IDR {{ number_format($pengajuan->cicilan_perbulan, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Asuransi per Bulan:</span>
                        <span>IDR {{ number_format($pengajuan->biaya_asuransi_perbulan, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between fw-bold">
                        <span>Total per Bulan:</span>
                        <span class="text-danger">IDR {{ number_format($pengajuan->cicilan_perbulan + $pengajuan->biaya_asuransi_perbulan, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM PEMBAYARAN MIDTRANS -->
    <div class="card">
        <div class="product-title-bar">
            <h4 class="mb-0 text-white">Form Pembayaran Kredit</h4>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form untuk input tanggal dan alamat -->
            <form id="payment-form">
                @csrf
                <input type="hidden" name="pengajuan_kredit_id" value="{{ $pengajuan->id }}">

                <div class="mb-3">
                    <label for="tgl_mulai_kredit" class="form-label">Tanggal Mulai Kredit</label>
                    <input type="date" name="tgl_mulai_kredit" id="tgl_mulai_kredit" class="form-control" required>
                    @error('tgl_mulai_kredit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="tgl_selesai_kredit" class="form-label">Tanggal Selesai Kredit</label>
                    <input type="date" id="tgl_selesai_kredit" class="form-control" disabled>
                </div>
                
                <div class="mb-3">
                    <label for="sisa_kredit" class="form-label">Sisa Kredit</label>
                    <input type="text" id="sisa_kredit" class="form-control" readonly>
                </div>

                <!-- Dropdown untuk memilih alamat atau form input -->
                <div class="mb-3">
                    <label for="alamat_id" class="form-label">Pilih Alamat Pengiriman</label>
                    <select name="alamat_id" id="alamat_id" class="form-control">
                        <option value="">-- Pilih Alamat --</option>
                        @if($pelanggan)
                            @for($i = 1; $i <= 3; $i++)
                                @if($pelanggan->{"alamat$i"} && $pelanggan->{"kota$i"} && $pelanggan->{"provinsi$i"} && $pelanggan->{"kode_pos$i"})
                                    <option value="{{ $i }}">
                                        Alamat {{ $i }}: {{ $pelanggan->{"alamat$i"} }}, {{ $pelanggan->{"kota$i"} }}, {{ $pelanggan->{"provinsi$i"} }} - {{ $pelanggan->{"kode_pos$i"} }}
                                    </option>
                                @endif
                            @endfor
                        @endif
                        <option value="new">Tambah Alamat Baru</option>
                    </select>
                </div>

                <!-- Form input alamat baru (awalnya tersembunyi) -->
                <div id="new_address_form" class="mb-3" style="display: none;">
                    <h6>Tambah Alamat Baru</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="new_alamat" class="form-label">Alamat</label>
                            <input type="text" name="new_alamat" id="new_alamat" class="form-control" placeholder="Masukkan alamat">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="new_kota" class="form-label">Kota</label>
                            <input type="text" name="new_kota" id="new_kota" class="form-control" placeholder="Masukkan kota">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="new_provinsi" class="form-label">Provinsi</label>
                            <input type="text" name="new_provinsi" id="new_provinsi" class="form-control" placeholder="Masukkan provinsi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="new_kode_pos" class="form-label">Kode Pos</label>
                            <input type="text" name="new_kode_pos" id="new_kode_pos" class="form-control" placeholder="Masukkan kode pos">
                        </div>
                    </div>
                </div>

                <button type="button" id="pay-midtrans" class="btn btn-primary w-100" data-pengajuan-id="{{ $pengajuan->id }}">Bayar Sekarang</button>
            </form>
        </div>
    </div>
    @endif
</div>

@php
    $lamaCicilan = $pengajuan->JenisCicilan->lama_cicilan ?? 0;
    $hargaKredit = $pengajuan->harga_kredit ?? 0;
    $dp = $pengajuan->dp ?? 0;
    $snapTokenUrl = route('pengajuan.snap-token', $pengajuan->id);
    $csrfToken = csrf_token();
@endphp

<!-- Sertakan Snap.js untuk Midtrans -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen input
        const tglMulaiInput = document.getElementById("tgl_mulai_kredit");
        const tglSelesaiInput = document.getElementById("tgl_selesai_kredit");
        const sisaKreditInput = document.getElementById("sisa_kredit");
        const payMidtransButton = document.getElementById("pay-midtrans");
        const alamatSelect = document.getElementById("alamat_id");
        const newAddressForm = document.getElementById("new_address_form");
    
        // Ambil data dari PHP
        const lamaCicilan = {{ $lamaCicilan }};
        const hargaKredit = {{ $hargaKredit }};
        const dp = {{ $dp }};
        const snapTokenUrl = "{{ $snapTokenUrl }}";
        const csrfToken = "{{ $csrfToken }}";
        const updatePaymentUrl = "{{ route('pengajuan.update-payment-status') }}";
    
        // Fungsi untuk menghitung tanggal selesai
        function updateTanggalSelesai() {
            const tglMulai = new Date(tglMulaiInput.value);
            if (!isNaN(tglMulai)) {
                const tglSelesai = new Date(tglMulai);
                tglSelesai.setMonth(tglSelesai.getMonth() + lamaCicilan);
    
                const formatDate = (date) => {
                    const yyyy = date.getFullYear();
                    const mm = String(date.getMonth() + 1).padStart(2, '0');
                    const dd = String(date.getDate()).padStart(2, '0');
                    return `${yyyy}-${mm}-${dd}`;
                };
    
                tglSelesaiInput.value = formatDate(tglSelesai);
            } else {
                tglSelesaiInput.value = '';
            }
        }
    
        // Set tanggal mulai default ke hari ini
        const today = new Date();
        const formatDate = (date) => {
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        };
        tglMulaiInput.value = formatDate(today);
        updateTanggalSelesai();
    
        // Update tanggal selesai saat tanggal mulai berubah
        tglMulaiInput.addEventListener('change', updateTanggalSelesai);
    
        // Hitung sisa kredit
        const sisa = hargaKredit - dp;
        const formatIDR = (val) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(val);
        };
        sisaKreditInput.value = formatIDR(sisa);
    
        // Pastikan elemen ditemukan
        if (!alamatSelect || !newAddressForm) {
            console.error('Element not found:', { alamatSelect, newAddressForm });
            return;
        }
    
        // Toggle form alamat baru
        function toggleAddressForm() {
            console.log('toggleAddressForm called, value:', alamatSelect.value);
            if (alamatSelect.value === 'new') {
                newAddressForm.style.display = 'block';
            } else {
                newAddressForm.style.display = 'none';
            }
        }
    
        // Tambahkan event listener untuk onchange
        alamatSelect.addEventListener('change', toggleAddressForm);
    
        // Panggil toggleAddressForm saat halaman dimuat untuk memastikan status awal
        toggleAddressForm();
    
        // ====== MIDTRANS PAYMENT ======
        payMidtransButton.addEventListener('click', function() {
            const tglMulaiKredit = tglMulaiInput.value;
            const alamatId = alamatSelect.value;
            let newAlamat = '', newKota = '', newProvinsi = '', newKodePos = '';
    
            if (!tglMulaiKredit) {
                Swal.fire('Error', 'Harap masukkan tanggal mulai kredit.', 'error');
                return;
            }
    
            if (alamatId === 'new') {
                newAlamat = document.getElementById('new_alamat').value;
                newKota = document.getElementById('new_kota').value;
                newProvinsi = document.getElementById('new_provinsi').value;
                newKodePos = document.getElementById('new_kode_pos').value;
    
                if (!newAlamat || !newKota || !newProvinsi || !newKodePos) {
                    Swal.fire('Error', 'Harap lengkapi semua field alamat baru.', 'error');
                    return;
                }
            } else if (!alamatId) {
                Swal.fire('Error', 'Harap pilih alamat atau tambah alamat baru.', 'error');
                return;
            }
    
            // Kirim data ke server untuk mendapatkan Snap Token
            const formData = new FormData();
            formData.append('pengajuan_kredit_id', {{ $pengajuan->id }});
            formData.append('tgl_mulai_kredit', tglMulaiKredit);
            formData.append('alamat_id', alamatId);
            if (alamatId === 'new') {
                formData.append('new_alamat', newAlamat);
                formData.append('new_kota', newKota);
                formData.append('new_provinsi', newProvinsi);
                formData.append('new_kode_pos', newKodePos);
            }
    
            fetch(snapTokenUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || 'Failed to fetch snap token: ' + response.statusText);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.token) {
                    snap.pay(data.token, {
                        onSuccess: function(result) {
                            console.log('Payment success result:', result);
                            // Kirim data ke server untuk memperbarui status
                            fetch(updatePaymentUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify({
                                    order_id: result.order_id,
                                    payment_type: result.payment_type || '',
                                    transaction_status: result.transaction_status || 'success',
                                    va_numbers: result.va_numbers || [],
                                    transaction_id: result.transaction_id || '',
                                    gross_amount: result.gross_amount || ''
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: 'Pembayaran berhasil diproses.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.href = '{{ route('pengajuan') }}';
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Gagal memperbarui status pembayaran.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error updating payment status:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat mengirim data ke server.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                        },
                        onPending: function(result) {
                            Swal.fire('Menunggu Pembayaran', 'Pembayaran sedang diproses.', 'info');
                        },
                        onError: function(result) {
                            Swal.fire('Gagal', 'Pembayaran gagal.', 'error');
                        },
                        onClose: function() {
                            Swal.fire('Info', 'Anda menutup popup pembayaran.', 'info');
                        }
                    });
                } else {
                    Swal.fire('Error', 'Gagal mendapatkan Snap Token: ' + (data.error || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan: ' + error.message, 'error');
            });
        });
    });
    </script>