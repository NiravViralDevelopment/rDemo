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
        @can('permission-list')
        <li class="nav-item {{ in_array(Route::currentRouteName(), ['permissions.index', 'permissions.edit', 'permissions.create']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('permissions.index') }}">
                <i class="bi bi-key sidebar-nav-icon" aria-hidden="true"></i>
                <span>Permissions</span>
            </a>
        </li>
        @endcan
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

         
    </ul>
  </aside>
  