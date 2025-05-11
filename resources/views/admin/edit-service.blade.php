<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service | Greenspace</title>
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
        
        .form-container {
            max-width: 700px;
            margin: 30px auto;
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(46, 139, 87, 0.1);
            border-top: 4px solid var(--primary-green);
        }
        
        h2 {
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
        
        .required-field::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        }
        
        .input-group-text {
            background-color: var(--light-green);
            border-color: #ced4da;
            color: var(--primary-green);
            font-weight: 500;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 139, 87, 0.3);
        }
        
        .btn-outline-secondary {
            color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 139, 87, 0.3);
        }
        
        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #dc3545;
        }
        
        textarea.form-control {
            resize: vertical;
        }
        
        /* Form section styling */
        .form-section {
            margin-bottom: 2rem;
            padding: 1.75rem;
            background-color: var(--light-green);
            border-radius: 10px;
            border-left: 4px solid var(--primary-green);
        }
        
        /* Nature-inspired decorative elements */
        .nature-decoration {
            position: absolute;
            opacity: 0.1;
            z-index: -1;
            color: var(--primary-green);
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
        
        @media (max-width: 768px) {
            .form-container {
                padding: 1.75rem;
                margin: 1rem auto;
            }
            
            h2 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Nature decorative elements -->
    <i class="fas fa-leaf nature-decoration leaf-1"></i>
    <i class="fas fa-seedling nature-decoration leaf-2"></i>

    <div class="container py-4">
        <div class="form-container">
            <h2><i class="fas fa-edit me-2"></i>Edit Service</h2>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.updateService', $service->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-section">
                    <!-- Service Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label required-field">Service Type</label>
                        <select class="form-select" id="type" name="type" required>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type }}" 
                                    @if($service->type == $type || old('type') == $type) selected @endif>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select the category this service belongs to</small>
                    </div>

                    <!-- Service Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label required-field">Service Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $service->name) }}" placeholder="Enter service name" required>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label required-field">Price (₱)</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" min="0" class="form-control" 
                                   id="price" name="price" value="{{ old('price', $service->price) }}" 
                                   placeholder="0.00" required>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-0">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="4" placeholder="Provide a detailed description of the service">{{ old('description', $service->description) }}</textarea>
                        <small class="text-muted">Optional: Detailed explanation of what this service includes</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.manageServices') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>