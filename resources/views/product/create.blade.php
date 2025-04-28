@extends('fe.master')

@section('content')
<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Pengajuan Kredit Motor</h4>  
    </div>
</div>

<div class="container-fluid contact py-5">
    <div class="container py-5">
        <div class="mb-3">
            <a href="{{ route('product') }}" class="btn btn-outline-danger fw-bold">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
        <div class="row g-5">
            <div class="col-lg-12 wow fadeInRight" data-wow-delay="0.4s">
                <div class="form-section bg-dark p-5 h-100">
                    <h1 class="display-4 text-white mb-4">Ajukan Sekarang</h1>
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
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" id="pengajuanForm">
                        @csrf
                        <div class="row g-4">
                            <!-- Bagian: Data Motor -->
                            <h3 class="text-white mb-0">Data Motor</h3>
                            <hr class="bg-white">
                            <input type="hidden" name="pelanggan_id" value="{{ Auth::guard('pelanggan')->user()->id }}">
                            <input type="hidden" name="tgl_pengajuan_kredit" value="{{ now()->format('Y-m-d') }}">
                            <input type="hidden" name="status_pengajuan" value="Menunggu Konfirmasi">
                            <input type="hidden" name="keterangan_status_pengajuan" value="Menunggu Konfirmasi">
                            <input type="hidden" name="motor_id" value="{{ $motor->id }}">
                            
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control" style="color: black;" value="{{ $motor->nama_motor }} {{ $motor->JenisMotor->jenis }} - {{ $motor->JenisMotor->merk }}">
                                    <label>Motor</label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control" style="color: black;" value="{{ number_format($motor->harga_jual ?? 0, 0, ',', '.') }}">
                                    <input type="hidden" name="harga_cash" value="{{ $motor->harga_jual ?? 0 }}">
                                    <label>Harga Cash</label>
                                </div>
                            </div>

                            <!-- Bagian: Asuransi & Cicilan -->
                            <h3 class="text-white mb-0">Asuransi & Cicilan</h3>
                            <hr class="bg-white">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="asuransi_id" id="asuransi_id" class="form-control @error('asuransi_id') is-invalid @enderror" style="color: black;" required>
                                        <option selected disabled style="color: black;">Pilih Jenis Asuransi</option>
                                        @foreach ($asuransi as $item)
                                            <option value="{{ $item->id }}" data-margin="{{ $item->margin_asuransi }}" style="color: black;">{{ $item->nama_asuransi }}</option>
                                        @endforeach
                                    </select>
                                    <label>Jenis Asuransi</label>
                                    @error('asuransi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control @error('biaya_asuransi_perbulan') is-invalid @enderror" style="color: black;" id="biaya_asuransi_perbulan_display">
                                    <input type="hidden" name="biaya_asuransi_perbulan" id="biaya_asuransi_perbulan">
                                    <label>Biaya Asuransi / Bulan</label>
                                    @error('biaya_asuransi_perbulan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="jenis_cicilan_id" id="jenis_cicilan_id" class="form-control @error('jenis_cicilan_id') is-invalid @enderror" style="color: black;" required>
                                        <option selected disabled style="color: black;">Pilih Jenis Cicilan</option>
                                        @foreach ($jenisCicilan as $cicilan)
                                            <option value="{{ $cicilan->id }}" data-margin-kredit="{{ $cicilan->margin_kredit }}" data-dp="{{ $cicilan->dp }}" data-tenor="{{ $cicilan->lama_cicilan }}" style="color: black;">{{ $cicilan->nama }} ({{ $cicilan->lama_cicilan }} bulan)</option>
                                        @endforeach
                                    </select>
                                    <label>Jenis Cicilan</label>
                                    @error('jenis_cicilan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control @error('harga_kredit') is-invalid @enderror" style="color: black;" id="harga_kredit_display">
                                    <input type="hidden" name="harga_kredit" id="harga_kredit">
                                    <label>Harga Kredit</label>
                                    @error('harga_kredit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control @error('dp') is-invalid @enderror" style="color: black;" id="dp_display">
                                    <input type="hidden" name="dp" id="dp">
                                    <label>Uang Muka (DP)</label>
                                    @error('dp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control @error('cicilan_perbulan') is-invalid @enderror" style="color: black;" id="cicilan_perbulan_display">
                                    <input type="hidden" name="cicilan_perbulan" id="cicilan_perbulan">
                                    <label>Cicilan / Bulan</label>
                                    @error('cicilan_perbulan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bagian: Dokumen Pendukung -->
                            <h3 class="text-white mb-0">Dokumen Pendukung</h3>
                            <hr class="bg-white">
                            <div class="col-12">
                                <label class="text-white mb-2">Upload KK</label>
                                <input type="file" name="url_kk" class="form-control @error('url_kk') is-invalid @enderror" style="color: black;" accept=".jpg,.jpeg,.png,.pdf" required>
                                @error('url_kk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="text-white mb-2">Upload KTP</label>
                                <input type="file" name="url_ktp" class="form-control @error('url_ktp') is-invalid @enderror" style="color: black;" accept=".jpg,.jpeg,.png,.pdf" required>
                                @error('url_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="text-white mb-2">Upload NPWP</label>
                                <input type="file" name="url_npwp" class="form-control @error('url_npwp') is-invalid @enderror" style="color: black;" accept=".jpg,.jpeg,.png,.pdf">
                                @error('url_npwp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="text-white mb-2">Upload Slip Gaji</label>
                                <input type="file" name="url_slip_gaji" class="form-control @error('url_slip_gaji') is-invalid @enderror" style="color: black;" accept=".jpg,.jpeg,.png,.pdf" required>
                                @error('url_slip_gaji')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="text-white mb-2">Upload Foto</label>
                                <input type="file" name="url_foto" class="form-control @error('url_foto') is-invalid @enderror" style="color: black;" accept=".jpg,.jpeg,.png,.pdf" required>
                                @error('url_foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 py-3 px-5">Ajukan Sekarang</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pengajuanForm');
    const asuransiSelect = document.getElementById('asuransi_id');
    const cicilanSelect = document.getElementById('jenis_cicilan_id');
    const biayaAsuransiDisplay = document.getElementById('biaya_asuransi_perbulan_display');
    const biayaAsuransiInput = document.getElementById('biaya_asuransi_perbulan');
    const hargaKreditDisplay = document.getElementById('harga_kredit_display');
    const hargaKreditInput = document.getElementById('harga_kredit');
    const dpDisplay = document.getElementById('dp_display');
    const dpInput = document.getElementById('dp');
    const cicilanPerbulanDisplay = document.getElementById('cicilan_perbulan_display');
    const cicilanPerbulanInput = document.getElementById('cicilan_perbulan');
    const hargaJual = {{ $motor->harga_jual ?? 0 }};

    // Fungsi format Rupiah
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateCalculations() {
        const selectedAsuransi = asuransiSelect.options[asuransiSelect.selectedIndex];
        const selectedCicilan = cicilanSelect.options[cicilanSelect.selectedIndex];

        // Biaya Asuransi
        const marginAsuransi = selectedAsuransi ? parseFloat(selectedAsuransi.getAttribute('data-margin')) : 0;
        const biayaAsuransi = marginAsuransi ? Math.round(marginAsuransi * hargaJual / 100) : 0;
        biayaAsuransiDisplay.value = biayaAsuransi ? formatRupiah(biayaAsuransi) : '';
        biayaAsuransiInput.value = biayaAsuransi || '';
        
        // DP
        const dpPercent = selectedCicilan ? parseFloat(selectedCicilan.getAttribute('data-dp')) : 0;
        const dp = dpPercent ? Math.round(hargaJual * dpPercent / 100) : 0;
        dpDisplay.value = dp ? formatRupiah(dp) : '';
        dpInput.value = dp || '';
        
        // Harga Kredit
        const marginKredit = selectedCicilan ? parseFloat(selectedCicilan.getAttribute('data-margin-kredit')) : 0;
        const hargaKredit = marginKredit ? Math.round(hargaJual - dp + (hargaJual * marginKredit / 100)) : 0;
        hargaKreditDisplay.value = hargaKredit ? formatRupiah(hargaKredit) : '';
        hargaKreditInput.value = hargaKredit || '';

        // Cicilan Per Bulan
        const tenor = selectedCicilan ? parseInt(selectedCicilan.getAttribute('data-tenor')) : 1;
        const cicilanPerbulan = (hargaKredit && dp && tenor) ? Math.round((hargaKredit) / tenor + biayaAsuransi) : 0;
        cicilanPerbulanDisplay.value = cicilanPerbulan ? formatRupiah(cicilanPerbulan) : '';
        cicilanPerbulanInput.value = cicilanPerbulan || '';
    }

    asuransiSelect.addEventListener('change', updateCalculations);
    cicilanSelect.addEventListener('change', updateCalculations);
});
</script>
@endsection