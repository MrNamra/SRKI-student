<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SRKI Subtation Portal</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ url('/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ url('/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ url('/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <!-- dropzonejs -->
        <link rel="stylesheet" href="{{ url('/plugins/dropzone/min/dropzone.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ url('/dist/css/adminlte.min.css') }}">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{url('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
        <!-- DataTable -->
        <link rel="stylesheet" href="{{url('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <style>
            @media (min-width: 768px) {
                body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper, body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer, body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
                    transition: margin-left .3s ease-in-out;
                    margin: 0;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50" >
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        {{-- <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                                class="fas fa-bars"></i></a> --}}
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{route('student.dashboard')}}" class="nav-link {{Request::is('student/dashboard')? 'active' : ''}}">Home</a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link">Contact</a>
                    </li>
                </ul>
    
                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    {{-- <!-- Notifications Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge">15</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-envelope mr-2"></i> 4 new messages
                                <span class="float-right text-muted text-sm">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-users mr-2"></i> 8 friend requests
                                <span class="float-right text-muted text-sm">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-file mr-2"></i> 3 new reports
                                <span class="float-right text-muted text-sm">2 days</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                        </div>
                    </li> --}}
                </ul>
                @if (auth()->guard('student')->check())
                    <form method="POST" action="{{route('student.logout')}}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                @endif
            </nav>
    
            <div class="content-wrapper" style="margin: 0">
                @yield('main')
            </div>
            <footer class="main-footer">
                <div class="float-right d-none d-sm-block">
                  <b>Version</b> 1.0.0
                </div>
                <strong>Devloped By <a target="_blank" href="https://instagram.com/oye_namu">Namra Ramsha</a>.</strong> All rights reserved.
            </footer>
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- DataTable -->
        <script src="{{url('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{url('/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{url('/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- Select2 -->
        <script src="{{url('/plugins/select2/js/select2.full.min.js')}}"></script>
        <!-- SweetAlert2 -->
        <script src="{{url('/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
        <!-- Toastr -->
        <script src="{{url('/plugins/toastr/toastr.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{url('/dist/js/adminlte.min.js')}}"></script>
        <!-- Select2 -->
        <script href="{{url('/plugins/select2/js/select2.full.min.js')}}"></script>
        @yield('js')
    </body>
</html>