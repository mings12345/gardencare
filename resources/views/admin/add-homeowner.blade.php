<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Homeowner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome for eye icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        .password-input-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Add Homeowner</h1>

        <!-- Add Homeowner Form -->
        <form method="POST" action="{{ route('admin.storeHomeowner') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <!-- Password with toggle -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="password-input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                </div>
            </div>
            
            <!-- Confirm Password with toggle -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="password-input-group">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation')"></i>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="submit" class="btn btn-success">Add Homeowner</button>
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
    </script>
</body>
</html>