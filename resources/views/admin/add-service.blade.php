<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Service - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .form-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .form-footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="form-container">
            <!-- Header Section -->
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Service</h2>
                    <a href="{{ route('admin.manageServices') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Services
                    </a>
                </div>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Section -->
            <form action="{{ route('admin.storeService') }}" method="POST">
                @csrf
                
                <!-- Service Name Field -->
                <div class="mb-4">
                    <label for="name" class="form-label required-field">Service Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" 
                           placeholder="Enter service name" required>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Price Field - Updated to use ₱ -->
                <div class="mb-4">
                    <label for="price" class="form-label required-field">Price (₱)</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" min="0" 
                               class="form-control @error('price') is-invalid @enderror" 
                               id="price" name="price" value="{{ old('price') }}" 
                               placeholder="0.00" required>
                        @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Description Field (Optional) -->
                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3" placeholder="Optional service description">{{ old('description') }}</textarea>
                </div>

                <!-- Form Footer -->
                <div class="form-footer">
                    <div class="d-flex justify-content-between">
                        <button type="reset" class="btn btn-outline-danger">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Service
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Optional: Form Validation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Prevent form submission if required fields are empty
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value;
                const price = document.getElementById('price').value;
                
                if (!name || !price) {
                    e.preventDefault();
                    alert('Please fill in all required fields');
                }
            });
        });
    </script>
</body>
</html>