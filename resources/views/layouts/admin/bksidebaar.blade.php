<!-- ======= Sidebar ======= -->

<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
      @can('Dashboard')
      <li class="nav-item">
          <a class="nav-link" href="{{route('dashboard')}}">
              <i class="bi bi-speedometer2 sidebar-nav-icon" aria-hidden="true"></i>
              <span>Dashboard</span>
          </a>
      </li>
      @endcan  
      @can('role-list')
      <li class="nav-item">
          <a class="nav-link" href="{{route('roles.index')}}">
              <i class="bi bi-shield-lock sidebar-nav-icon" aria-hidden="true"></i>
              <span>Roles</span>
          </a>
      </li>
      @endcan  
      @can('user-list')
      <li class="nav-item">
          <a class="nav-link" href="{{route('users.index')}}">
              <i class="bi bi-people sidebar-nav-icon" aria-hidden="true"></i>
              <span>Users</span>
          </a>
      </li>
      @endcan  
      @can('Order-Item')
      <li class="nav-item">
          <a class="nav-link" href="{{route('order-item')}}">
              <i class="bi bi-box-seam sidebar-nav-icon" aria-hidden="true"></i>
              <span>Order-Items</span>
          </a>
      </li>
      @endcan  
      @can('Manage Order')
      <li class="nav-item">
          <a class="nav-link" href="{{route('manage-order')}}">
              <i class="bi bi-clipboard-check sidebar-nav-icon" aria-hidden="true"></i>
              <span>Manage Orders</span>
          </a>
      </li>
      @endcan       
  </ul>
</aside>
