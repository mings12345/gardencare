<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2c7744;
            --secondary-green: #5cb85c;
            --light-green: #e8f5e9;
            --dark-green: #1b5e20;
            --accent-yellow: #ffd54f;
        }
        
        body {
            background-color: #f8f9fa;
            background-image: linear-gradient(120deg, #e8f5e9 0%, #f8f9fa 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .form-container {
            max-width: 650px;
            margin: 40px auto;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-top: 4px solid var(--primary-green);
            background-color: white;
        }
        
        .page-header {
            color: var(--primary-green);
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-green);
            box-shadow: 0 0 0 0.25rem rgba(92, 184, 92, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-outline-secondary {
            color: var(--dark-green);
            border-color: var(--dark-green);
            transition: all 0.3s ease;
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--dark-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .input-group-text {
            background-color: var(--secondary-green);
            color: white;
            border-color: var(--secondary-green);
        }
        
        .form-label {
            color: #495057;
            font-weight: 500;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            border-left: 4px solid #dc3545;
        }
        
        .form-control, .form-select {
            padding: 0.6rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }
        
        .form-control:hover, .form-select:hover {
            border-color: var(--secondary-green);
        }
        
        .leaf-bg {
            position: fixed;
            top: 20px;
            right: 20px;
            opacity: 0.1;
            z-index: -1;
            font-size: 15rem;
            color: var(--primary-green);
            transform: rotate(15deg);
        }
        
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
        }
        
        textarea {
            min-height: 120px;
        }
        
        /* Preview image styling */
        .image-preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            display: none;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .file-input-label {
            cursor: pointer;
            display: block;
        }
    </style>
</head>
<body>
    <div class="leaf-bg">
        <i class="fas fa-leaf"></i>
    </div>
    
    <div class="container py-4">
        <div class="form-container">
            <h2 class="page-header">
                <i class="fas fa-seedling me-2" style="color: var(--secondary-green);"></i>Add New Service
            </h2>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Service Type -->
                <div class="mb-4">
                    <label for="type" class="form-label required-field">Service Type</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-tags"></i>
                        </span>
                        <select class="form-select" id="type" name="type" required>
                            <option value="" disabled selected>Select service type</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type }}" @if(old('type') == $type) selected @endif>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Service Name -->
                <div class="mb-4">
                    <label for="name" class="form-label required-field">Service Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-signature"></i>
                        </span>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name') }}" required>
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <label for="price" class="form-label required-field">Price (₱)</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-peso-sign"></i>
                        </span>
                        <input type="number" step="0.01" min="0" class="form-control" 
                               id="price" name="price" value="{{ old('price') }}" required>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="form-label">Service Image</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-image"></i>
                        </span>
                        <input type="file" class="form-control" id="image" name="image" 
                               accept="image/*" onchange="previewImage(event)">
                    </div>
                    <img id="imagePreview" class="image-preview" src="#" alt="Image Preview">
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-align-left"></i>
                        </span>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4 pt-2">
                    <a href="{{ route('admin.manageServices') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Service
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
                preview.src = '#';
            }
        }
    </script>
</body>
</html>