<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Homeowner | GardenCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2e8b57;
            --dark-green: #1a5632;
            --light-green: #e8f5e9;
            --accent-green: #4caf50;
            --text-color: #333333;
            --muted-text: #6c757d;
        }
        
        body {
            background-color: #f5f9f5;
            color: var(--text-color);
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin-top: 2rem;
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(46, 139, 87, 0.1);
            border-top: 4px solid var(--primary-green);
        }
        
        h1 {
            color: var(--primary-green);
            margin-bottom: 1.75rem;
            font-weight: 600;
            text-align: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        }
        
        .password-input-group {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--muted-text);
            z-index: 5;
        }
        
        .btn-success {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            width: 100%;
            margin-top: 1rem;
            border-radius: 8px;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }
        
        .btn-success:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 139, 87, 0.3);
        }
        
        .form-section {
            margin-bottom: 2rem;
            padding: 1.75rem;
            background-color: var(--light-green);
            border-radius: 10px;
            border-left: 4px solid var(--primary-green);
        }
        
        .form-section h5 {
            color: var(--primary-green);
            margin-bottom: 1.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .leaf-icon {
            color: var(--primary-green);
            margin-right: 10px;
        }
        
        .header-icon {
            color: var(--primary-green);
            margin-right: 12px;
        }
        
        small.text-muted {
            color: var(--muted-text) !important;
            font-size: 0.85rem;
        }

        .profile-image-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--primary-green);
            margin: 0 auto 1rem;
        }
        
        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .btn-outline-primary {
            border-color: var(--primary-green);
            color: var(--primary-green);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--light-green);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1.75rem;
                margin: 1rem auto;
            }
            
            h1 {
                font-size: 1.75rem;
            }
            
            .form-section {
                padding: 1.25rem;
            }
        }
        
        /* Nature-inspired decorative elements */
        .nature-decoration {
            position: absolute;
            opacity: 0.1;
            z-index: -1;
        }
        
        .leaf-1 {
            top: 10%;
            left: 5%;
            font-size: 120px;
            transform: rotate(-15deg);
        }
        
        .leaf-2 {
            bottom: 10%;
            right: 5%;
            font-size: 100px;
            transform: rotate(25deg);
        }
    </style>
</head>
<body>
    <!-- Nature decorative elements -->
    <i class="fas fa-leaf nature-decoration leaf-1"></i>
    <i class="fas fa-leaf nature-decoration leaf-2"></i>

    <div class="container mt-5">
        <h1><i class="fas fa-user-shield header-icon"></i>Add Homeowner</h1>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

       <form method="POST" action="{{ route('admin.storeHomeowner') }}" enctype="multipart/form-data">
        @csrf
            
            <div class="text-center mb-4">
                <div class="profile-image-container">
                    <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile" id="profileImagePreview">
                </div>
                <label for="profile_image" class="btn btn-outline-primary btn-sm mt-2">
                    <i class="fas fa-camera me-1"></i> Add Profile Photo
                    <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*">
                </label>
            </div>
            
            <div class="form-section">
                <h5><i class="fas fa-user-circle leaf-icon"></i>Personal Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$" title="Please enter a valid .com email address" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Enter physical address" required>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h5><i class="fas fa-lock leaf-icon"></i>Account Security</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Create password (min 8 chars)" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                        </div>
                        <small class="text-muted">Include numbers and special characters</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation')"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('admin.manageHomeowners') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-user-plus me-2"></i>Register Homeowner
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Preview image before upload
        document.getElementById('profile_image').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById('profileImagePreview');
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>
</body>
</html>