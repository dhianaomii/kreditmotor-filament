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
                        <span>Harga Motor:</span>
                        <span>IDR {{ number_format($pengajuan->harga_kredit, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>DP:</span>
                        <span>IDR {{ number_format($pengajuan->dp, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Total Biaya Asuransi:</span>
                        <span>IDR {{ number_format($pengajuan->biaya_asuransi_perbulan * $pengajuan->jenisCicilan->lama_cicilan, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between fw-bold">
                        <span>Total yang Harus Dibayar:</span>
                        <span class="text-danger">IDR {{ number_format($pengajuan->dp + ($pengajuan->cicilan_perbulan * $pengajuan->jenisCicilan->lama_cicilan), 2, ',', '.') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Info Cicilan</h5>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Jangka Waktu:</span>
                        <span>{{ $pengajuan->jenisCicilan->lama_cicilan ?? '-' }} bulan</span>
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

    <!-- FORM PEMBAYARAN -->
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

            <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
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
                
                <div class="mb-3" hidden>
                    <label for="status_kredit" class="form-label">Status Kredit</label>
                    <input type="text" name="status_kredit" id="status_kredit" class="form-control" value="Dicicil" required>
                    {{-- @error('status_kredit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror --}}
                </div>
                
                <div class="mb-3">
                    <label for="sisa_kredit" class="form-label">Sisa Kredit</label>
                    <input type="text" id="sisa_kredit" class="form-control" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="keterangan_status_kredit" class="form-label">Keterangan (Opsional)</label>
                    <textarea name="keterangan_status_kredit" id="keterangan_status_kredit" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="metode_pembayaran_id" class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran_id" id="metode_pembayaran_id" class="form-select" required>
                        <option value="">-- Pilih Metode --</option>
                        @foreach($metodePembayarans as $metode)
                            <option value="{{ $metode->id }}">{{ $metode->metode_pembayaran }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bukti_bayar" class="form-label">Upload Bukti Bayar</label>
                    <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">Bayar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

@php
    $lamaCicilan = $pengajuan->jenisCicilan->lama_cicilan ?? 0;
    $hargaKredit = $pengajuan->harga_kredit ?? 0;
    $dp = $pengajuan->dp ?? 0;
@endphp

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tglMulaiInput = document.getElementById("tgl_mulai_kredit");
        const tglSelesaiInput = document.getElementById("tgl_selesai_kredit");
        const lamaCicilan = {{ $lamaCicilan }};

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
        updateTanggalSelesai(); // Hitung tanggal selesai untuk default

        // Update tanggal selesai setiap tanggal mulai berubah
        tglMulaiInput.addEventListener('change', updateTanggalSelesai);

        // ====== SISA KREDIT ======
        const hargaKredit = {{ $hargaKredit }};
        const dp = {{ $dp }};
        const sisa = hargaKredit - dp;

        // Format ke IDR untuk tampilan
        const formatIDR = (val) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(val);
        };

        document.getElementById("sisa_kredit").value = formatIDR(sisa);
    });
</script>