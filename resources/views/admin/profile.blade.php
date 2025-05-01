<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | GreenSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #4caf50;
            --pale-green: #e8f5e9;
            --dark-green: #1b5e20;
            --accent-green: #81c784;
        }
        
        body {
            background-color: var(--pale-green);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
        }
        
        .navbar {
            background-color: var(--primary-green);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand i {
            margin-right: 8px;
        }
        
        .main-content {
            padding: 30px 20px;
        }
        
        .header {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .header h1 {
            color: var(--dark-green);
            font-weight: 600;
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        .header h1:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 3px;
            background-color: var(--accent-green);
            border-radius: 2px;
        }
        
        .profile-card {
            max-width: 700px;
            margin: 0 auto;
            border-radius: 15px;
            border: none;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: var(--light-green);
            color: white;
            border-bottom: none;
            padding: 20px;
        }
        
        .card-body {
            padding: 30px;
            background-color: white;
        }
        
        .form-label {
            color: var(--dark-green);
            font-weight: 500;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--light-green);
            box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.3);
        }
        
        .alert-success {
            background-color: var(--pale-green);
            border-color: var(--accent-green);
            color: var(--dark-green);
        }
        
        .img-thumbnail {
            border-radius: 50%;
            border: 3px solid var(--accent-green);
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        
        .avatar-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profile-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }
        
        .form-floating > .form-control {
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }
        
        .form-floating > label {
            padding: 1rem 0.75rem;
        }
        
        footer {
            background-color: var(--primary-green);
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-leaf"></i> GardenCare Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container main-content">
        <div class="header">
            <h1><i class="fas fa-user-edit"></i> My Profile</h1>
        </div>

        <div class="card profile-card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-id-card"></i> Personal Information</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="avatar-container">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="Profile Picture" class="img-thumbnail">
                        @else
                            <div style="width: 120px; height: 120px; border-radius: 50%; background-color: var(--accent-green); display: inline-flex; justify-content: center; align-items: center; font-size: 3rem; color: white;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div class="mt-3">
                            <label for="avatar" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-camera"></i> Change Photo
                            </label>
                            <input type="file" class="form-control d-none" id="avatar" name="avatar">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required placeholder="Your Name">
                                <label for="name"><i class="fas fa-user me-2"></i>Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="your@email.com">
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Profile
                        </button>
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-lock me-2"></i> Change Password
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="mb-0">&copy; 2025 GardenCare Admin Panel. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>