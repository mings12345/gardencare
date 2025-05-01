<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | GreenThumb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;
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

        .profile-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .profile-header h1 {
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
            font-size: 28px;
        }

        .profile-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background-color: white;
            transition: var(--transition);
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .profile-card .card-body {
            padding: 30px;
        }

        .profile-avatar-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--light-color);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            border-color: var(--accent-color);
        }

        .profile-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-light);
        }

        .form-label {
            font-weight: 500;
            color: var(--dark-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 25px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--dark-color);
            border-color: var(--dark-color);
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-light);
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>My Profile</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="profile-card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="profile-avatar-container">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" class="profile-avatar" alt="Profile Picture">
                    @else
                        <div class="profile-avatar bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-4x text-muted"></i>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="avatar" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="avatar" name="avatar">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Change Password</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3 position-relative">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('current_password')"></i>
                        </div>

                        <div class="col-md-4 mb-3 position-relative">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('new_password')"></i>
                        </div>

                        <div class="col-md-4 mb-3 position-relative">
                            <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('new_password_confirmation')"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                        
                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash-alt me-1"></i> Delete Account
                        </a>
                    </div>
                </form>

                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->created_at->format('M Y') }}</div>
                        <div class="stat-label">Member Since</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</div>
                        <div class="stat-label">Last Login</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $user->user_type }}</div>
                        <div class="stat-label">Role</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                    <p class="text-danger"><strong>Warning:</strong> All your data will be permanently removed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.profile.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>