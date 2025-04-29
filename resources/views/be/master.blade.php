<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$title}}</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" />
    
    <!-- CSS Dependencies -->
    @filamentStyles
    @livewireStyles
    
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
    
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="{{ asset('assets/js/select.dataTables.min.css') }}">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filament-custom.css') }}">
    
    <!-- Sweet Alert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Vite Resources -->
    @vite('resources/css/app.css')
    <style>
      #toast {
        visibility: hidden;
        min-width: 250px;
        background-color: #f44336;
        color: white;
        text-align: center;
        border-radius: 8px;
        padding: 12px 20px;
        position: fixed;
        z-index: 9999;
        right: 30px;
        top: 30px;
        font-size: 16px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: visibility 0s, opacity 0.5s ease-in-out;
        opacity: 0;
      }
    
      #toast.show {
        visibility: visible;
        opacity: 1;
      }
    </style>
    
</head>
<body>
  <div class="container-scroller">
    @yield('navbar')
    
    <div class="container-fluid page-body-wrapper">
      @yield('sidebar')
      
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        
        @yield('footer')
      </div>
    </div>
  </div>

    <!-- Core JS Dependencies -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    
    <!-- Plugin JS -->
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a5a3fb0aea.js" crossorigin="anonymous"></script>
    
    <!-- Sweet Alert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Vite Resources -->
    @filamentScripts
    @vite('resources/js/app.js')

    <div id="toast">{{ session('error') }}</div>

    <script>
      @if (session('error'))
        const toast = document.getElementById("toast");
        toast.classList.add("show");
    
        setTimeout(() => {
          toast.classList.remove("show");
        }, 3000); // hilang dalam 3 detik
      @endif
    </script>
    
    @stack('scripts')

    
</body>
</html>