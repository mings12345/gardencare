<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="form-container bg-white">
            <h2 class="mb-4"><i class="fas fa-edit me-2"></i>Edit Service</h2>
            
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
                </div>

                <!-- Service Name -->
                <div class="mb-3">
                    <label for="name" class="form-label required-field">Service Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $service->name) }}" required>
                </div>

                <!-- Price -->
                <div class="mb-3">
                    <label for="price" class="form-label required-field">Price (₱)</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" min="0" class="form-control" 
                               id="price" name="price" value="{{ old('price', $service->price) }}" required>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3">{{ old('description', $service->description) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
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