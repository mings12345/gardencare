<!-- resources/views/admin/partials/sidebar.blade.php -->
<div class="sidebar">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.manageBookings') ? 'active' : '' }}" href="{{ route('admin.manageBookings') }}">
                    <i class="fas fa-calendar-alt me-2"></i> Manage Bookings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.manageUsers') ? 'active' : '' }}" href="{{ route('admin.manageUsers') }}">
                    <i class="fas fa-users me-2"></i> Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.manageServices') ? 'active' : '' }}" href="{{ route('admin.manageServices') }}">
                    <i class="fas fa-tools me-2"></i> Manage Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.manageRatings') ? 'active' : '' }}" href="{{ route('admin.manageRatings') }}">
                    <i class="fas fa-comments me-2"></i> Manage Ratings
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #4CAF50;
        padding-top: 20px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar .nav-link {
        padding: 12px 20px;
        color: white;
        font-size: 16px;
        border-left: 3px solid transparent;
        transition: all 0.3s;
    }

    .sidebar .nav-link:hover {
        background-color: #45a049;
        border-left: 3px solid white;
    }

    .sidebar .nav-link.active {
        background-color: #3d8b40;
        border-left: 3px solid white;
        font-weight: 500;
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }
</style>