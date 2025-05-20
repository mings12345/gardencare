<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gardener | Greenspace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Same CSS as in edit-homeowner.blade.php */
        :root {
            --greenspace-primary: #2e7d32;
            --greenspace-secondary: #81c784;
            --greenspace-light: #e8f5e9;
        }
        body {
            background-color: #f5f9f5;
        }
        .greenspace-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
            overflow: hidden;
        }
        .greenspace-card-header {
            background-color: var(--greenspace-primary);
            color: white;
            padding: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: var(--greenspace-primary);
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            border-color: var(--greenspace-secondary);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }
        .btn-greenspace {
            background-color: var(--greenspace-primary);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
        }
        .btn-greenspace:hover {
            background-color: #1b5e20;
        }
        .btn-outline-greenspace {
            border: 1px solid var(--greenspace-primary);
            color: var(--greenspace-primary);
        }
        .btn-outline-greenspace:hover {
            background-color: var(--greenspace-light);
        }
        .profile-image-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--greenspace-secondary);
            margin-bottom: 1rem;
        }

        .profile-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="greenspace-card">
                    <div class="greenspace-card-header">
                        <h1 class="h4 mb-0"><i class="bi bi-flower2 me-2"></i>Edit Gardener</h1>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    
                       <form method="POST" action="{{ route('admin.updateGardener', $gardener->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                        <div class="text-center mb-4">
                        <div class="profile-image-container mx-auto">
                            @if($gardener->profile_image)
                                <img src="{{ asset('storage/' . $gardener->profile_image) }}" alt="Profile Image" id="profileImagePreview">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile" id="profileImagePreview">
                            @endif
                        </div>
                        <label for="profile_image" class="btn btn-outline-greenspace btn-sm mt-2">
                            <i class="bi bi-camera me-1"></i> Change Photo
                            <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*">
                        </label>
                    </div>

                            <div class="mb-4">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $gardener->name) }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $gardener->email) }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone', $gardener->phone) }}">
                            </div>
                            <div class="mb-4">
                                <label for="address" class="form-label">Physical Address</label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       value="{{ old('address', $gardener->address) }}">
                            </div>
                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ route('admin.manageGardeners') }}" class="btn btn-outline-greenspace">
                                    <i class="bi bi-arrow-left me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-greenspace">
                                    <i class="bi bi-save me-1"></i> Update Gardener
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script>
            // Preview image before upload
            document.getElementById('profile_image').addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = document.getElementById('profileImagePreview');
                    preview.src = URL.createObjectURL(file);
                }
            });
        </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>