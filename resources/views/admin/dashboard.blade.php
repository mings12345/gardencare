<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | GreenThumb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32; /* Darker green */
            --secondary-color: #4CAF50;
            --accent-color: #8BC34A;
            --light-color: #F1F8E9;
            --dark-color: #1B5E20;
            --text-color: #333;
            --text-light: #666;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            background-image: url('https://www.transparenttextures.com/patterns/leaves.png');
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        /* Sidebar styling */
        .sidebar {
            height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--dark-color) 100%);
            padding-top: 30px;
            box-shadow: var(--shadow);
            z-index: 1000;
            transition: var(--transition);
        }

        .sidebar-header {
            padding: 0 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar-header h3 {
            color: white;
            font-weight: 600;
            margin: 0;
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            transition: var(--transition);
            margin: 5px 15px;
            border-radius: 8px;
            font-weight: 500;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar a i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        /* Main content styling */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
            transition: var(--transition);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .header h1 {
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
            font-size: 28px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            position: relative;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }

        .user-profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
            min-width: 200px;
            z-index: 100;
            overflow: hidden;
            display: none;
        }

        .user-profile-dropdown.show {
            display: block;
        }

        .user-profile-dropdown a {
            padding: 10px 15px;
            display: block;
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .user-profile-dropdown a:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
        }

        .user-profile-dropdown a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Card grid styling */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            transition: var(--transition);
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background-color: white;
            position: relative;
            border-top: 4px solid var(--accent-color);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 25px;
            position: relative;
        }

        .card-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 40px;
            color: rgba(139, 195, 74, 0.2);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 15px 0;
        }

        .card-text {
            font-size: 14px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card-text i {
            color: var(--accent-color);
        }

        /* Stats cards specific colors */
        .card-bookings {
            border-top-color: #2196F3;
        }
        .card-bookings .card-icon {
            color: rgba(33, 150, 243, 0.2);
        }

        .card-users {
            border-top-color: #9C27B0;
        }
        .card-users .card-icon {
            color: rgba(156, 39, 176, 0.2);
        }

        .card-earnings {
            border-top-color: #FF9800;
        }
        .card-earnings .card-icon {
            color: rgba(255, 152, 0, 0.2);
        }

        .card-services {
            border-top-color: #009688;
        }
        .card-services .card-icon {
            color: rgba(0, 150, 136, 0.2);
        }

        .card-ratings {
            border-top-color: #E91E63;
        }
        .card-ratings .card-icon {
            color: rgba(233, 30, 99, 0.2);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                overflow: hidden;
            }
            .sidebar a span {
                display: none;
            }
            .sidebar a i {
                margin-right: 0;
                font-size: 22px;
            }
            .sidebar-header {
                display: none;
            }
            .main-content {
                margin-left: 80px;
            }
        }

        @media (max-width: 768px) {
            .card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>GardenCare Admin</h3>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="active">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.manageBookings') }}">
            <i class="fas fa-calendar-alt"></i> <span>Manage Bookings</span>
        </a>
        <a href="{{ route('admin.manageUsers') }}">
            <i class="fas fa-users"></i> <span>Manage Users</span>
        </a>
        <a href="{{ route('admin.manageServices') }}">
            <i class="fas fa-tools"></i> <span>Manage Services</span>
        </a>
        <a href="{{ route('admin.ratings.index') }}">
            <i class="fas fa-comments"></i> <span>Manage Ratings</span>
        </a>
        <a href="{{ route('admin.reports') }}">
            <i class="fas fa-chart-bar"></i> <span>Reports</span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <div class="user-profile" onclick="toggleDropdown()">
                <img src="https://ui-avatars.com/api/?name=Admin&background=4CAF50&color=fff" alt="Admin">
                <span>Admin</span>
                <!-- Update the user-profile-dropdown div in dashboard.blade.php -->
            <div class="user-profile-dropdown" id="profileDropdown">
                <a href="{{ route('admin.profile') }}"><i class="fas fa-user"></i> Profile</a>
                <a href="{{ route('logout') }}" 
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Welcome back!</strong> Here's what's happening with your garden services today.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Card Grid -->
        <div class="card-grid">
            <!-- Total Bookings Card -->
            <a href="{{ route('admin.manageBookings') }}" class="text-decoration-none">
                <div class="card card-bookings">
                    <div class="card-body">
                        <i class="fas fa-calendar-check card-icon"></i>
                        <h5 class="card-title">Total Bookings</h5>
                        <div class="card-value">{{ $totalBookings }}</div>
                        <p class="card-text">
                            <i class="fas fa-arrow-up"></i> 12% from last month
                        </p>
                    </div>
                </div>
            </a>

            <!-- Total Users Card -->
            <a href="{{ route('admin.manageUsers') }}" class="text-decoration-none">
                <div class="card card-users">
                    <div class="card-body">
                        <i class="fas fa-users card-icon"></i>
                        <h5 class="card-title">Total Users</h5>
                        <div class="card-value">{{ $totalHomeowners + $totalGardeners + $totalServiceProviders }}</div>
                        <p class="card-text">
                            <i class="fas fa-user-shield"></i> {{ $totalHomeowners }} homeowners
                        </p>
                    </div>
                </div>
            </a>

            <!-- Total Earnings Card -->
            <div class="card card-earnings">
                <div class="card-body">
                    <span class="card-icon">₱</span>
                    <h5 class="card-title">My Earnings</h5>
                    <div class="card-value">₱{{ number_format($totalEarnings, 2) }}</div>
                    <p class="card-text">
                        <i class="fas fa-chart-line"></i> 8% growth this month
                    </p>
                </div>
            </div>

            <!-- Total Services Card -->
            <a href="{{ route('admin.manageServices') }}" class="text-decoration-none">
                <div class="card card-services">
                    <div class="card-body">
                        <i class="fas fa-leaf card-icon"></i>
                        <h5 class="card-title">Total Services</h5>
                        <div class="card-value">{{ $services->count() }}</div>
                        <p class="card-text">
                            <i class="fas fa-star"></i> {{ $popularServicesCount }} popular services
                        </p>
                    </div>
                </div>
            </a>

            <!-- Feedback Management Card -->
            <a href="{{ route('admin.ratings.index') }}" class="text-decoration-none">
                <div class="card card-ratings">
                    <div class="card-body">
                        <i class="fas fa-star card-icon"></i>
                        <h5 class="card-title">Customer Ratings</h5>
                        <div class="card-value">{{ $ratings->count() }}</div>
                        <p class="card-text">
                            <i class="fas fa-smile"></i> {{ $averageRating }}/5 average
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple animation for cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Toggle profile dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close the dropdown if clicked outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.user-profile') && !event.target.closest('.user-profile')) {
                const dropdowns = document.getElementsByClassName('user-profile-dropdown');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        });
    </script>
</body>
</html>