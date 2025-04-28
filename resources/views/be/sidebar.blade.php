<!-- resources/views/partials/_sidebar.blade.php -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <!-- Dashboard: Semua role -->
    <li class="nav-item">
      <a class="nav-link" href="{{ url('dashboard') }}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <!-- Jenis Motor: Hanya Admin -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ url('jenis-motor') }}">
        <i class="fa-regular fa-folder-closed menu-icon"></i>
        <span class="menu-title">Jenis Motor</span>
      </a>
    </li>
    @endif

    <!-- Motor: Hanya Admin -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ url('motor') }}">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Motor</span>
      </a>
    </li>
    @endif

    <!-- Pelanggan: Admin, CEO, Marketing -->
    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']))
    <li class="nav-item">
      <a class="nav-link" href="{{ url('pelanggan') }}">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">Pelanggan</span>
      </a>
    </li>
    @endif

    <!-- Asuransi: Admin, Marketing -->
    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'marketing']))
    <li class="nav-item">
      <a class="nav-link" href="{{ url('asuransi') }}">
        <i class="fa-regular fa-address-card menu-icon"></i>
        <span class="menu-title">Asuransi</span>
      </a>
    </li>
    @endif

    <!-- Jenis Cicilan: Admin, Marketing -->
    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'marketing']))
    <li class="nav-item">
      <a class="nav-link" href="{{ url('jenis-cicilan') }}">
        <i class="icon-tag menu-icon"></i>
        <span class="menu-title">Jenis Cicilan</span>
      </a>
    </li>
    @endif

    <!-- Metode Pembayaran: Hanya Admin -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ url('metode-pembayaran') }}">
        <i class="fa-regular fa-money-bill-1 menu-icon"></i>
        <span class="menu-title">Metode Pembayaran</span>
      </a>
    </li>
    @endif

    <!-- Pengajuan Kredit: Admin, CEO, Marketing -->
    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']))
    <li class="nav-item">
      <a class="nav-link" href="{{ url('pengajuan-kredit') }}">
        <i class="fa-regular fa-handshake menu-icon"></i>
        <span class="menu-title">Pengajuan Kredit</span>
      </a>
    </li>
    @endif

    <!-- Kredit: Admin, CEO, Marketing -->
    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']))
    <li class="nav-item">
      <a class="nav-link" href="{{ url('kredit') }}">
        <i class="mdi mdi-credit-card-multiple menu-icon"></i>
        <span class="menu-title">Kredit</span>
      </a>
    </li>
    @endif

    <!-- Angsuran: Admin, CEO, Marketing -->
    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'marketing']))
    <li class="nav-item">
      <a class="nav-link" href="{{ url('angsuran') }}">
        <i class="mdi mdi-calendar-multiple menu-icon"></i>
        <span class="menu-title">Angsuran</span>
      </a>
    </li>
    @endif

    <!-- Pengiriman: Admin, CEO, Kurir -->
    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'ceo', 'kurir']))
    <li class="nav-item">
      <a class="nav-link" href="{{ url('pengiriman') }}">
        <i class="mdi mdi-truck-delivery menu-icon"></i>
        <span class="menu-title">Pengiriman</span>
      </a>
    </li>
    @endif

    <!-- User: Hanya Admin -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ url('user') }}">
        <i class="fa-regular fa-money-bill-1 menu-icon"></i>
        <span class="menu-title">User</span>
      </a>
    </li>
    @endif
  </ul>
</nav>