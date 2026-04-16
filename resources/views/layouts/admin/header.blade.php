 <!-- ======= Header ======= -->
 <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between h100 spBack">
            <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center header-brand">
                <img class="header-brand-logo" src="{{ asset('admin/img/logo.png') }}" alt="HARIHAR ERP Logo">
                <p class="header-brand-title mb-0">HARIHAR ERP</p>
            </a>
        </div><!-- End Logo -->
        
        @php
            $routeName = Route::currentRouteName();
            $pageTitles = [
                'dashboard'     => 'Dashboard',             
            ];
            $pageTitle = $pageTitles[$routeName] ?? '';
        @endphp
        <nav class="header-nav">
        <p class="chngTxt">{{$pageTitle}}</p>
        <ul class="d-flex align-items-center">           

            


            <li class="nav-item dropdown pe-3">
                @php 
                    $userRoles = Auth::user()->getRoleNames();
                @endphp
            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <p class="d-md-block dropdown-toggle ps-2 mb-0 userTxt"><span>Welcome,</span> {{ Auth::user()->name}}</p>
            </a><!-- End Profile Iamge Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile dropUl">
                <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}">
                    <i class="bi bi-person"></i>
                    <span>My Profile</span>
                </a>
                </li> 
                <li>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <!-- <i class="bi bi-box-arrow-right"></i> -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline fullWd noMbLg">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">
                            <i class="bi bi-box-arrow-right"></i> <!-- Logout icon -->
                            <span>Logout</span>
                        </button>
                    </form>
                    <!-- <span>Sign Out</span> -->
                </a>
                </li>

            </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
        </nav><!-- End Icons Navigation -->
  </header><!-- End Header -->