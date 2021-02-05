<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <img src="{{ asset('asset/dist/img/logo.png')}}" alt="InvSys" class="brand-image img-circle elevation-3"
          style="opacity: .8">
    <!-- CHANGE BUSINESS LOGO HERE -->
    <span class="brand-text font-weight-light"></span>InvSys
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('asset/dist/img/user.png')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        @if(Auth::check())
          <a href="{{route('user.show', Auth::user()->id) }}" class="d-block">{{ Auth::user()->first_name}} {{ Auth::user()->last_name}}</a>
        @else
        <a href="#" class="d-block">You are not logged in!</a>
        @endif
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{ url('home') }}" class="nav-link {{ Request::is('home*') ? 'active' : ''}}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        @role('A')
        <li class="nav-item">
          <a href="{{ route('user.index')}}" class="nav-link {{ Request::is('user*') ? 'active' : ''}}">
            <i class="nav-icon fas fa-user"></i>
            <p>User</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('content.index') }}" class="nav-link {{ Request::is('content*') ? 'active' : ''}}">
            <i class="nav-icon fas fa-cogs"></i>
            <p>
              Content
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ Request::is('master*') ? 'menu-open' : ''}}">
          <a href="#" class="nav-link {{ Request::is('master/vendor*') ? 'active' : ''}}">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('addon.index')}}" class="nav-link {{ Request::is('master/addon*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-plus"></i>
                <p>Addon</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('product.index')}}" class="nav-link {{ Request::is('master/product*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-archive"></i>
                <p>Product</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('category.index')}}" class="nav-link {{ Request::is('master/category*') ? 'active' : ''}}">
                <i class="nav-icon fas fa-box-open"></i>
                <p>Category</p>
              </a>
            </li>
          </ul>
        </li>
        @endrole
        <li class="nav-item has-treeview {{ Request::is('transaction*') ? 'menu-open' : ''}}">
          <a href="#" class="nav-link {{ Request::is('transaction*') ? 'active' : ''}}">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Transaction
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('sales.index') }}" class="nav-link {{ Request::is('transaction/sales*') ? 'active' : ''}}">
              <i class="fas fa-cash-register nav-icon"></i>
                <p>Sales Order</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('purchase-order.index') }}" class="nav-link {{ Request::is('transaction/purchase-order*') ? 'active' : ''}}">
              <i class="fas fa-shopping-cart nav-icon"></i>
                <p>Purchase Order</p>
              </a>
            </li>
            @role('A')
            <li class="nav-item">
              <a href="{{ route('transaction/stock') }}" class="nav-link {{ Request::is('transaction/stock*') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Stock</p>
              </a>
            </li>
            @endrole
          </ul>
        </li>
        <li class="nav-item has-treeview {{ Request::is('stock/report*') ? 'menu-open' : ''}}">
          <a href="#" class="nav-link  {{ Request::is('stock/report*') ? 'active' : ''}}">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>
              Reports
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('stock/report') }}" class="nav-link {{ Request::is('stock/report*') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Stock Reports</p>
              </a>
            </li>
            @role('A')
            <li class="nav-item">
              <a href="{{ route('/categoriesSummary') }}" class="nav-link {{ Request::is('/categoriesSummary*') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Categories Sold</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('/salesSummary') }}" class="nav-link {{ Request::is('/salesSummary*') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Customer Orders</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('/expensesSummary') }}" class="nav-link {{ Request::is('/expensesSummary*') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Expenses List</p>
              </a>
            </li>
            @endrole
          </ul>
        </li>
        <li class="nav-header">USER SETTINGS</li>
        <li class="nav-item">
          <a href="{{ route('user.edit', Auth::user()->id) }}" class="nav-link">
            <i class="nav-icon fas fa-user-edit"></i>
            <p>Update Profile</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('password') }}" class="nav-link">
            <i class="nav-icon fas fa-asterisk"></i>
            <p>Change Password</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/calendar') }}" class="nav-link">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>Calendar</p>
          </a>
        </li>
        
        <li class="nav-item"> 
          <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off"></i> Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>

          </a>
        </form>
        </li>
      </ul>
    </nav>
  </div>
</aside>