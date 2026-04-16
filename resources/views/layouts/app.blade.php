<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicons -->
    <link href="{{ asset('admin/img/cropped_favicon.png') }}" rel="icon" type="image/png">
    <link href="{{ asset('admin/img/cropped_favicon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('admin')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('admin')}}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{asset('admin')}}/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="{{asset('admin')}}/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="{{asset('admin')}}/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="{{asset('admin')}}/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="{{asset('admin')}}/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="{{asset('admin')}}/css/style.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a href="#" class="logo d-flex align-items-center">
                    <img src="{{asset('admin')}}/img/logo.png" alt="">
                    <span class="d-none d-lg-block">Vasu Healthcare</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Add left-side navigation items if needed -->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            <!-- Add guest links if needed -->
                        @else
                            <li><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>
                            <li><a class="nav-link" href="{{ route('roles.index') }}">Manage Role</a></li>
                            <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Vendor JS Files -->
    <script src="{{asset('admin')}}/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="{{asset('admin')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('admin')}}/vendor/chart.js/chart.umd.js"></script>
    <script src="{{asset('admin')}}/vendor/echarts/echarts.min.js"></script>
    <script src="{{asset('admin')}}/vendor/quill/quill.js"></script>
    <script src="{{asset('admin')}}/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="{{asset('admin')}}/vendor/tinymce/tinymce.min.js"></script>
    <script src="{{asset('admin')}}/vendor/php-email-form/validate.js"></script>


   
    <!-- Template Main JS File -->
    <script src="{{asset('admin')}}/js/main.js"></script>
</body>
</html>
