<li class="nav-item dropdown" wire:poll.10s="updateNotifikasi">
    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
        <i class="icon-bell mx-0"></i>
        @if($jumlahNotifikasi > 0)
            <span class="count">{{ $jumlahNotifikasi }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
        @foreach(\App\Models\PengajuanKredit::where('status_pengajuan', 'Menunggu Konfirmasi')->get() as $pengajuan)
            <a href="pengajuan-kredit" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <div class="preview-icon bg-warning">
                        <i class="mdi mdi-bell-ring mx-0"></i>
                    </div>
                </div>
                <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">Pengajuan Kredit Baru</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">Pelanggan: {{ $pengajuan->Pelanggan->nama_pelanggan }}</p>
                </div>
            </a>
        @endforeach
    </div>
</li>
