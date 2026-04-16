<!-- ======= Sidebar ======= -->

<aside id="sidebar" class="sidebar">
  <div class="clickSlick">
    <i class="bi bi-chevron-right toggle-sidebar-btn"></i>
  </div>
    
    <ul class="sidebar-nav" id="sidebar-nav">
        
        <li class="nav-item {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
            <a class="nav-link" href="{{route('dashboard')}}">
                <i class="bi bi-speedometer2 sidebar-nav-icon" aria-hidden="true"></i>
                <span>Dashboard</span>
            </a>
        </li>
       
        @can('role-list')
        @if(empty(auth()->user()->project_id))
        <li class="nav-item {{ in_array(Route::currentRouteName(), ['roles.index', 'roles.edit', 'roles.show','roles.create']) ? 'active' : '' }}">
            <a class="nav-link" href="{{route('roles.index')}}">
                <i class="bi bi-shield-lock sidebar-nav-icon" aria-hidden="true"></i>
                <span>Roles</span>
            </a>
        </li>
        @endif
        @endcan  
        @can('user-list')
        @if(empty(auth()->user()->project_id))
        <li class="nav-item {{ in_array(Route::currentRouteName(), ['users.index', 'users.edit', 'users.show','users.create']) ? 'active' : '' }}">
            <a class="nav-link" href="{{route('users.index')}}">
                <i class="bi bi-people sidebar-nav-icon" aria-hidden="true"></i>
                <span>Users</span>
            </a>
        </li>
        @endif
        @endcan

         

        <li class="nav-item {{ in_array(Route::currentRouteName(), ['houses.index', 'shops.index']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('houses.index') }}">
                <i class="bi bi-house-door sidebar-nav-icon" aria-hidden="true"></i>
                <span>House List</span>
            </a>
        </li>

        <li class="nav-item {{ in_array(Route::currentRouteName(), ['partners.index', 'partners.create', 'partners.edit']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('partners.index') }}">
                <i class="bi bi-person-vcard sidebar-nav-icon" aria-hidden="true"></i>
                <span>Partners</span>
            </a>
        </li>

        <li class="nav-item {{ in_array(Route::currentRouteName(), ['vendors.index', 'vendors.create', 'vendors.edit']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendors.index') }}">
                <i class="bi bi-truck sidebar-nav-icon" aria-hidden="true"></i>
                <span>Vendors</span>
            </a>
        </li>

        <li class="nav-item {{ in_array(Route::currentRouteName(), ['shops.index']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('shops.index') }}">
                <i class="bi bi-shop sidebar-nav-icon" aria-hidden="true"></i>
                <span>Shop List</span>
            </a>
        </li>

        <li class="nav-item {{ in_array(Route::currentRouteName(), ['bookings.index']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('bookings.index') }}">
                <i class="bi bi-calendar-check sidebar-nav-icon" aria-hidden="true"></i>
                <span>Bookings</span>
            </a>
        </li>

        <li class="nav-item {{ in_array(Route::currentRouteName(), ['banks.index', 'banks.create', 'banks.edit']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('banks.index') }}">
                <i class="bi bi-bank sidebar-nav-icon" aria-hidden="true"></i>
                <span>Banks</span>
            </a>
        </li>
            
    </ul>
  </aside>
  