<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" style="z-index: 10;">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <a class="navbar-brand brand-logo me-5" href=""><img src="{{asset('assets/images/logo.png')}}" class="me-2" alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini" href=""><img src="{{asset('assets/images/logo-mini.svg')}}" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="icon-menu"></span>
    </button>
    
    
    <!-- notification -->
    <ul class="navbar-nav navbar-nav-right">
      @if(in_array(Auth::user()->role, ['admin', 'marketing']))
        @livewire('notifikasi-kredit')
      @endif
      <!-- Profile Button -->
      
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown" id="profileDropdown">
          <!-- Welcome Badge with Notification Dot -->
          <div class="position-relative me-2">
            <div class="d-flex align-items-center bg-primary text-white rounded-pill px-3 py-1">
              <i class="ti-user me-2"></i>
              <span class="fw-bold">{{ Auth::user()->name }}</span>
            </div>
          </div>
        </a>
        
        <!-- Dropdown Menu -->
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <div class="w-max px-4 py-3">
            <div class="d-flex align-items-center">
              <img src="{{ asset('assets/images/faces/dhianaomi.jpg') }}" class="rounded-circle me-3" width="48" height="48" alt="profile">
              <div>
                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                <small class="text-muted">{{ Auth::user()->email }}</small>
              </div>
            </div>
          </div>
          
          <div class="dropdown-divider"></div>
          
          <a class="dropdown-item" href="#">
            <i class="ti-user text-primary me-2"></i> Profile
          </a>
          <a class="dropdown-item" href="#">
            <i class="ti-settings text-primary me-2"></i> Settings
          </a>
          
          <div class="dropdown-divider"></div>
          
          <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="ti-power-off text-primary me-2"></i> Logout
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </a>
        </div>
      </li>
    </ul>
    
    <!-- collapse navbar button -->
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="icon-menu"></span>
    </button>
  </div>
</nav>