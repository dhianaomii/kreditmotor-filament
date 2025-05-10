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

            <!-- Form untuk input tanggal dan memulai pembayaran Midtrans -->
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

                <button type="button" id="pay-midtrans" class="btn btn-success w-100" data-pengajuan-id="{{ $pengajuan->id }}">Bayar dengan Midtrans</button>
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

    // Ambil data dari PHP
    const lamaCicilan = {{ $lamaCicilan }};
    const hargaKredit = {{ $hargaKredit }};
    const dp = {{ $dp }};
    const snapTokenUrl = "{{ $snapTokenUrl }}";
    const csrfToken = "{{ $csrfToken }}";

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

    // ====== MIDTRANS PAYMENT ======
    payMidtransButton.addEventListener('click', function() {
        const tglMulaiKredit = tglMulaiInput.value;
        if (!tglMulaiKredit) {
            alert('Harap masukkan tanggal mulai kredit.');
            return;
        }

        // Kirim tanggal mulai kredit ke server sebelum meminta snap token
        fetch(snapTokenUrl + '?tgl_mulai_kredit=' + encodeURIComponent(tglMulaiKredit), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.error || 'Failed to fetch snap token: ' + response.statusText);
                }).catch(() => {
                    throw new Error('Failed to parse error response: ' + response.statusText);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.token) {
                snap.pay(data.token, {
                    onSuccess: function(result) {
                        alert('Pembayaran berhasil!');
                        window.location.href = '{{ route('pengajuan') }}';
                    },
                    onPending: function(result) {
                        alert('Pembayaran sedang diproses.');
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal.');
                    },
                    onClose: function() {
                        alert('Anda menutup popup pembayaran.');
                        fetchStatus();
                    }
                });
            } else {
                alert('Gagal mendapatkan Snap Token: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });

        // Fungsi untuk memeriksa status
        function fetchStatus() {
            fetch(snapTokenUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error fetching status:', data.error);
                } else {
                    console.log('Status:', data.status);
                }
            })
            .catch(error => console.error('Error fetching status:', error));
        }
    });
});
</script>