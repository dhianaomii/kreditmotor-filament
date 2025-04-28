<li class="nav-item dropdown" wire:poll.30s="updateNotifikasi">
    <a class="nav-link count-indicator dropdown-toggle position-relative" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
        <i class="icon-bell mx-0"></i>
        @if($jumlahNotifikasi > 0)
            <span class="count position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" style="
                width: 18px;
                height: 18px;
                font-size: 0.6rem;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
            ">
            {{ $jumlahNotifikasi }}
            </span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end navbar-dropdown p-0" aria-labelledby="notificationDropdown" style="width: 350px;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center p-3 bg-primary text-white">
            <h6 class="mb-0 text-white">
                <i class="mdi mdi-bell-outline me-2"></i>
                Notifications
            </h6>
            @if($jumlahNotifikasi > 0)
                <span class="badge bg-white text-primary">{{ $jumlahNotifikasi }} new</span>
            @endif
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs nav-justified border-bottom" style="padding: 0 1rem;">
            <li class="nav-item">
                <a class="nav-link active py-3" data-bs-toggle="tab" href="#pengajuanTab" onclick="event.stopPropagation()">
                    <i class="mdi mdi-bell-ring-outline me-1"></i> Pengajuan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-3" data-bs-toggle="tab" href="#batalTab" onclick="event.stopPropagation()">
                    <i class="mdi mdi-close-circle-outline me-1"></i> Batal
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" style="max-height: 300px; overflow-y: auto;">
            <!-- Pengajuan Baru Tab -->
            <div class="tab-pane fade show active p-2" id="pengajuanTab">
                @php $pengajuanBaru = \App\Models\PengajuanKredit::where('status_pengajuan', 'Menunggu Konfirmasi')->get(); @endphp
                
                @if($pengajuanBaru->count() > 0)
                    @foreach($pengajuanBaru as $pengajuan)
                        <a href="{{ route('pengajuan-kredit') }}" class="dropdown-item d-flex align-items-start py-2">
                            <div class="me-3 text-warning">
                                <i class="mdi mdi-bell-ring-outline fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Pengajuan Kredit Baru</h6>
                                <p class="mb-1 text-muted small">Pelanggan: {{ $pengajuan->Pelanggan->nama_pelanggan }}</p>
                                <p class="text-muted small mb-0">
                                    <i class="mdi mdi-clock-outline"></i> {{ $pengajuan->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="mdi mdi-bell-off-outline d-block fs-2 mb-2"></i>
                        <p>Tidak ada pengajuan baru</p>
                    </div>
                @endif
            </div>

            <!-- Dibatalkan Tab -->
            <div class="tab-pane fade p-2" id="batalTab">
                @php $pengajuanBatal = \App\Models\PengajuanKredit::where('status_pengajuan', 'Dibatalkan Pembeli')->get(); @endphp
                
                @if($pengajuanBatal->count() > 0)
                    @foreach($pengajuanBatal as $pengajuan)
                        <a href="{{ route('pengajuan-kredit') }}" class="dropdown-item d-flex align-items-start py-2">
                            <div class="me-3 text-danger">
                                <i class="mdi mdi-close-circle-outline fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Pengajuan Dibatalkan</h6>
                                <p class="mb-1 text-muted small">Pelanggan: {{ $pengajuan->Pelanggan->nama_pelanggan }}</p>
                                @if($pengajuan->keterangan_status_pengajuan)
                                    <p class="small mb-1 bg-light p-2 rounded">
                                        <i class="mdi mdi-information-outline"></i> {{ $pengajuan->keterangan_status_pengajuan }}
                                    </p>
                                @endif
                                <p class="text-muted small mb-0">
                                    <i class="mdi mdi-clock-outline"></i> {{ $pengajuan->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="mdi mdi-close-box-outline d-block fs-2 mb-2"></i>
                        <p>Tidak ada pembatalan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="d-flex justify-content-center p-2 border-top">
            <a href="{{ route('pengajuan-kredit') }}" class="text-primary small">
                <i class="mdi mdi-eye-outline me-1"></i> Lihat Semua
            </a>
        </div>
    </div>
</li>

<script>
    // Mencegah dropdown menutup saat mengklik tab
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.nav-tabs .nav-link');
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>