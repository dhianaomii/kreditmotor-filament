@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Detail Pengajuan Kredit</h4>  
        </div>
    </div>

    <div class="container py-5">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fw-bold text-dark-blue">Detail Pengajuan Kredit</h2>
            <div>
                @if($angsuranList->count() < $kredit->PengajuanKredit->jenisCicilan->lama_cicilan && $kredit->status_kredit !== 'Lunas')
                    <button type="button" class="btn btn-red" data-bs-toggle="modal" data-bs-target="#modalBayarAngsuran">
                        <i class="bi bi-credit-card me-2"></i>Bayar Angsuran
                    </button>
                @endif
                {{-- @if($kredit->status_kredit == 'Lunas') --}}
                {{-- <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#modalAjukanPengiriman">
                    <i class="bi bi-truck me-2"></i>Ajukan Pengiriman
                </button> --}}
                {{-- @endif --}}
                <a href="{{ route('pengajuan') }}" class="btn btn-outline-red ms-2">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="product-title-bar">
                        <h4 class="mb-0 text-white">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Detail Pengajuan Kredit Motor
                        </h4>
                        <div>
                            <i class="bi bi-credit-card text-danger fs-4"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <!-- Data Pengajuan -->
                        <div class="mb-4">
                            <h5 class="text-primary fw-semibold border-bottom pb-2">
                                <i class="bi bi-info-circle me-2"></i>Data Pengajuan
                            </h5>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-motorcycle text-primary fs-4"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="card-subtitle mb-1 text-muted">Motor</h6>
                                                    <p class="card-text fw-bold">{{ $kredit->PengajuanKredit->motor->nama_motor ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-check-circle text-primary fs-4"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="card-subtitle mb-1 text-muted">Status</h6>
                                                    <p class="card-text">
                                                        <span class="badge bg-{{ $kredit->status_kredit == 'Dicicil' ? 'warning' : 
                                                        ($kredit->status_kredit == 'Macet' ? 'danger' : ($kredit->status_kredit == 'Lunas' ? 'info' : 'primary')) }} p-2">
                                                            {{ $kredit->status_kredit }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Kredit -->
                        <div class="mb-4">
                            <h5 class="text-primary fw-semibold border-bottom pb-2">
                                <i class="bi bi-cash-coin me-2"></i>Data Kredit
                            </h5>
                            <div class="row mt-3">
                                <!-- Total Kredit -->
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-currency-dollar text-primary fs-4"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="card-subtitle mb-1 text-muted">Total Kredit</h6>
                                                    <p class="card-text fw-bold text-danger">IDR {{ number_format($kredit->PengajuanKredit->harga_kredit, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Jumlah Sudah Dibayar -->
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="card-subtitle mb-1 text-muted">Sudah Dibayar</h6>
                                                    @if ($kredit->jumlah_sudah_dibayar > 0)
                                                        <p class="card-text fw-bold text-success">IDR {{ number_format($kredit->jumlah_sudah_dibayar, 0, ',', '.') }}</p>
                                                    @else
                                                        <p class="card-text fw-bold text-muted">Belum ada pembayaran</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Sisa Kredit -->
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-hourglass-split text-warning fs-4"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="card-subtitle mb-1 text-muted">Sisa Kredit</h6>
                                                    <p class="card-text fw-bold text-warning">IDR {{ number_format($kredit->sisa_kredit, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($angsuranList->count() >= $kredit->PengajuanKredit->jenisCicilan->lama_cicilan)
                                    <div class="col-md-12 mt-3">
                                        <div class="alert alert-info d-flex align-items-center">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            <div>Selamat! Semua angsuran telah dilunasi.</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Riwayat Angsuran -->
                        <div>
                            <h5 class="text-primary fw-semibold border-bottom pb-2">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Angsuran
                            </h5>
                            
                            @if($angsuranList->isNotEmpty())
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Angsuran Ke</th>
                                                <th>Bulan</th>
                                                <th>Jumlah Bayar</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($angsuranList as $angsuran)
                                                <tr>
                                                    <td>{{ $angsuran->angsuran_ke }}</td>
                                                    <td>{{ $angsuran->tgl_bayar ? \Carbon\Carbon::parse($angsuran->tgl_bayar)->translatedFormat('F Y') : '-' }}</td>
                                                    <td class="fw-bold text-danger">IDR {{ number_format($angsuran->total_bayar, 0, ',', '.') }}</td>
                                                    <td>{{ $angsuran->keterangan ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3 d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <div>Belum ada angsuran yang dibayarkan.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Bayar Angsuran -->
<div class="modal fade" id="modalBayarAngsuran" tabindex="-1" aria-labelledby="modalBayarAngsuranLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <!-- Modal Header with Gradient Background -->
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white fw-bold" id="modalBayarAngsuranLabel">
                    <i class="bi bi-credit-card-2-front me-2"></i>Bayar Angsuran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body with Card Style Elements -->
            <div class="modal-body">
                <div class="mb-3 px-2">
                    <h6 class="mb-0 fw-bold">Informasi Pembayaran</h6>
                    <p class="text-muted small mb-0">Silakan pilih bulan angsuran dan unggah bukti pembayaran</p>
                </div>

                <form id="formBayarAngsuran" action="{{ route('cicilan.store', $kredit->PengajuanKredit->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="bulan" class="form-label fw-semibold">
                            <i class="bi bi-calendar3 me-1 text-danger"></i>Bulan Angsuran
                        </label>
                        <select class="form-select form-select-md border" id="bulan" name="bulan" required>
                            <option value="">-- Pilih Bulan Angsuran --</option>
                            @php
                                $lamaCicilan = $kredit->PengajuanKredit->jenisCicilan->lama_cicilan;
                                $angsuranTerbayar = $angsuranList->pluck('tgl_bayar')->map(function($tgl) {
                                    return $tgl ? \Carbon\Carbon::parse($tgl)->format('Y-m') : null;
                                })->filter()->toArray();
                                $tanggalMulai = \Carbon\Carbon::parse($kredit->PengajuanKredit->tanggal_disetujui ?? $kredit->PengajuanKredit->tgl_pengajuan_kredit ?? now());
                                $bulanTersedia = [];
                                for ($i = 0; $i < $lamaCicilan; $i++) {
                                    $bulan = $tanggalMulai->copy()->addMonths($i);
                                    $bulanFormat = $bulan->format('Y-m');
                                    if (!in_array($bulanFormat, $angsuranTerbayar)) {
                                        $bulanTersedia[] = [
                                            'value' => $bulan->startOfMonth()->format('Y-m-d'),
                                            'label' => $bulan->translatedFormat('F Y'),
                                        ];
                                    }
                                }
                            @endphp
                            @foreach($bulanTersedia as $bulan)
                                <option value="{{ $bulan['value'] }}">{{ $bulan['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="jumlah_bayar" class="form-label fw-semibold">
                            <i class="bi bi-cash-stack me-1 text-danger"></i>Jumlah Bayar
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white fw-bold">Rp</span>
                            <input type="text" class="form-control bg-white border" id="jumlah_bayar" name="jumlah_bayar" value="{{ number_format($kredit->PengajuanKredit->cicilan_perbulan, 0, ',', '.') }}" readonly>
                        </div>
                        <div class="form-text text-end">Total angsuran yang harus dibayar bulan ini</div>
                    </div>

                    <div class="mb-4">
                        <label for="bukti_angsuran" class="form-label fw-semibold">
                            <i class="bi bi-file-earmark-arrow-up me-1 text-danger"></i>Bukti Pembayaran
                        </label>
                        <input type="file" class="form-control" id="bukti_angsuran" name="bukti_angsuran" accept="image/jpeg,image/png,image/jpg,image/gif" required>
                        <div class="form-text">Unggah file dalam format JPEG, PNG, JPG, atau GIF (maks. 2MB).</div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer with Enhanced Buttons -->
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <div class="d-flex w-100 gap-2">
                    <button type="button" class="btn btn-outline-secondary flex-fill py-2" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" form="formBayarAngsuran" class="btn btn-danger flex-fill py-2">
                        <i class="bi bi-check2-circle me-1"></i>Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <!-- Modal Ajukan Pengiriman -->
<div class="modal fade" id="modalAjukanPengiriman" tabindex="-1" aria-labelledby="modalAjukanPengirimanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white fw-bold" id="modalAjukanPengirimanLabel">
                    <i class="bi bi-truck me-2"></i>Ajukan Pengiriman
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="mb-3 px-2">
                    <h6 class="mb-0 fw-bold">Informasi Pengiriman</h6>
                    <p class="text-muted small mb-0">Pilih alamat dari profil atau masukkan alamat baru</p>
                </div>

                <form id="formAjukanPengiriman" action="{{ route('kirim.store', $kredit->PengajuanKredit->id) }}" method="POST">
                    @csrf
                    <!-- Input Tersembunyi -->
                    <input type="hidden" name="kredit_id" value="{{ $kredit->id }}">
                    <input type="hidden" name="status_kirim" value="Sedang Dikirim">
                    <input type="hidden" name="tanggal_pengiriman" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    <input type="hidden" name="no_telepon" value="{{ $pelanggan->no_telp ?? '' }}">
                    <input type="hidden" name="catatan" value="">

                    <!-- Pilihan Alamat -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1 text-success"></i>Pilih Alamat Pengiriman
                        </label>
                        <select class="form-select" id="alamat_type" name="alamat_type" required onchange="toggleAlamatForm()">
                            <option value="">-- Pilih Alamat --</option>
                            @if($pelanggan->alamat1)
                                <option value="alamat1">Alamat 1: {{ $pelanggan->alamat1 . ', ' . $pelanggan->kota1 . ', ' . $pelanggan->provinsi1 . ', ' . $pelanggan->kode_pos1 }}</option>
                            @endif
                            @if($pelanggan->alamat2)
                                <option value="alamat2">Alamat 2: {{ $pelanggan->alamat2 . ', ' . $pelanggan->kota2 . ', ' . $pelanggan->provinsi2 . ', ' . $pelanggan->kode_pos2 }}</option>
                            @endif
                            @if($pelanggan->alamat3)
                                <option value="alamat3">Alamat 3: {{ $pelanggan->alamat3 . ', ' . $pelanggan->kota3 . ', ' . $pelanggan->provinsi3 . ', ' . $pelanggan->kode_pos3 }}</option>
                            @endif
                            <option value="manual">Masukkan Alamat Baru</option>
                        </select>
                    </div>

                    <!-- Form Alamat Manual -->
                    <div class="mb-4" id="manual_alamat_form" style="display: none;">
                        <label for="alamat_pengiriman" class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1 text-success"></i>Alamat Pengiriman Baru
                        </label>
                        <textarea class="form-control" id="alamat_pengiriman" name="alamat_pengiriman" rows="3" placeholder="Masukkan alamat lengkap pengiriman"></textarea>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="provinsi" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-success"></i>Provinsi
                                </label>
                                <input type="text" class="form-control" id="provinsi" name="provinsi" placeholder="Masukkan provinsi">
                            </div>
                            <div class="col-md-6">
                                <label for="kode_pos" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1 text-success"></i>Kode Pos
                                </label>
                                <input type="text" class="form-control" id="kode_pos" name="kode_pos" placeholder="Masukkan kode pos">
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Input untuk Alamat yang Dipilih -->
                    <input type="hidden" id="selected_alamat" name="alamat_pengiriman" value="">
                </form>
            </div>
            
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <div class="d-flex w-100 gap-2">
                    <button type="button" class="btn btn-outline-secondary flex-fill py-2" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" form="formAjukanPengiriman" class="btn btn-success flex-fill py-2">
                        <i class="bi bi-check2-circle me-1"></i>Ajukan Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAlamatForm() {
    const alamatType = document.getElementById('alamat_type').value;
    const manualAlamatForm = document.getElementById('manual_alamat_form');
    const selectedAlamat = document.getElementById('selected_alamat');
    
    // Reset nilai alamat_pengiriman
    selectedAlamat.value = '';

    if (alamatType === 'manual') {
        manualAlamatForm.style.display = 'block';
    } else {
        manualAlamatForm.style.display = 'none';
        // Isi alamat_pengiriman berdasarkan pilihan
        if (alamatType === 'alamat1') {
            selectedAlamat.value = '{{ $pelanggan->alamat1 . ', ' . $pelanggan->provinsi1 . ', ' . $pelanggan->kode_pos1 }}';
        } else if (alamatType === 'alamat2') {
            selectedAlamat.value = '{{ $pelanggan->alamat2 . ', ' . $pelanggan->provinsi2 . ', ' . $pelanggan->kode_pos2 }}';
        } else if (alamatType === 'alamat3') {
            selectedAlamat.value = '{{ $pelanggan->alamat3 . ', ' . $pelanggan->provinsi3 . ', ' . $pelanggan->kode_pos3 }}';
        }
    }
}

// Validasi form sebelum submit
document.getElementById('formAjukanPengiriman').addEventListener('submit', function(e) {
    const alamatType = document.getElementById('alamat_type').value;
    const alamatPengiriman = document.getElementById('alamat_pengiriman')?.value;
    const provinsi = document.getElementById('provinsi')?.value;
    const kode_pos = document.getElementById('kode_pos')?.value;
    const selectedAlamat = document.getElementById('selected_alamat');

    if (alamatType === 'manual') {
        if (!alamatPengiriman || !provinsi || !kode_pos) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data Alamat Tidak Lengkap',
                text: 'Silakan isi alamat, provinsi, dan kode pos.',
                confirmButtonText: 'OK'
            });
        } else {
            selectedAlamat.value = `${alamatPengiriman}, ${provinsi}, ${kode_pos}`;
        }
    }
});
</script> --}}

<script>
   // Efek transisi fade-out
    document.querySelectorAll('.read-more, .btn-red, .btn-outline-red').forEach(link => {
        link.addEventListener('click', function(event) {
            if (this.tagName === 'A' && !this.closest('form') && !this.closest('.modal')) {
                event.preventDefault();
                const href = this.getAttribute('href');
                document.body.classList.add('fade-out');
                setTimeout(() => {
                    window.location.href = href;
                }, 500);
            }
        });
    });

    // Hilangkan spinner
    window.addEventListener('load', function () {
        const spinner = document.getElementById('spinner');
        if (spinner) {
            spinner.classList.remove('show');
        }
    });

    // SweetAlert untuk status Lunas
    @if ($kredit->status_kredit === 'Lunas')
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Selamat!',
                text: 'Kredit Anda telah lunas!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        });
    @endif
</script>
@endsection