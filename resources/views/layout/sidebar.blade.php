<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('home')}}">
        <div class="sidebar-brand-text">Tracking Scooter</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item{{ (request()->is('/')) ? ' active' : '' }}">
        <a class="nav-link " href="{{route('home')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu
    </div>

    <li class="nav-item{{ (request()->is('scooter')) ? ' active' : '' }}">
        <a class="nav-link " href="{{route('scooter')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Scooter</span></a>
    </li>

    <li class="nav-item{{ (request()->is('history')) ? ' active' : '' }}">
        <a class="nav-link " href="{{route('history')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>History</span></a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->