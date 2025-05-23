<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services | GardenCare</title>
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
            --gardening-color: #28a745;
            --landscaping-color: #17a2b8;
        }
        
        body {
            background-color: #f5f9f5;
            color: var(--text-color);
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin-top: 2rem;
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(46, 139, 87, 0.1);
            border-top: 4px solid var(--primary-green);
        }
        
        h1 {
            color: var(--primary-green);
            font-weight: 600;
            text-align: center;
            font-size: 2rem;
        }
        
        .action-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .btn-outline-secondary {
            color: var(--primary-green);
            border-color: var(--primary-green);
            transition: all 0.3s;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 139, 87, 0.3);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .btn-success {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
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
        
        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }
        
        .alert-success {
            background-color: #d4edda;
            border-color: var(--primary-green);
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(46, 139, 87, 0.08);
            border: none;
        }
        
        .table th {
            background-color: var(--light-green);
            color: var(--primary-green);
            font-weight: 600;
            border-color: #e0f2f1;
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        .table img {
            transition: transform 0.3s ease;
        }

        .table img:hover {
            transform: scale(1.5);
            z-index: 10;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #e0f2f1;
            font-size: 0.95rem;
        }
        
        .table tbody tr:hover {
            background-color: rgba(232, 245, 233, 0.5);
        }
        
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge-gardening {
            background-color: var(--gardening-color);
            color: white;
        }
        
        .badge-landscaping {
            background-color: var(--landscaping-color);
            color: white;
        }
        
        .description-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
            transition: all 0.3s;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        }
        
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            transition: all 0.3s;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
        
        .btn-sm {
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
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
            .container {
                padding: 1.75rem;
                margin: 1rem auto;
            }
            
            h1 {
                font-size: 1.75rem;
            }
            
            .action-header {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Nature decorative elements -->
    <i class="fas fa-leaf nature-decoration leaf-1"></i>
    <i class="fas fa-tree nature-decoration leaf-2"></i>

    <div class="container mt-5">
        <!-- Header with Back Button and Title -->
        <div class="action-header">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            <h1><i class="fas fa-seedling me-2"></i>Service Management</h1>
            <div></div> <!-- Empty div for spacing balance -->
        </div>

        <!-- Success Message (if any) -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons mb-4">
            <a href="{{ route('admin.addService') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Add New Service
            </a>
        </div>

        <!-- Services Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Provider</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td><strong>{{ $service->name }}</strong></td>
                        <td class="description-cell" title="{{ $service->description }}">
                            {{ $service->description }}
                        </td>
                        <td>
                            @if($service->type == 'Gardening')
                                <span class="badge badge-gardening rounded-pill">
                                    <i class="fas fa-leaf me-1"></i> Gardening
                                </span>
                            @else
                                <span class="badge badge-landscaping rounded-pill">
                                    <i class="fas fa-tree me-1"></i> Landscaping
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($service->user)
                                {{ $service->user->name }} (ID: {{ $service->user_id }})
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                        <td>
                            @if($service->image)
                                <img src="{{ asset('images/services/' . $service->image) }}" 
                                    alt="{{ $service->name }}" 
                                    style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>₱{{ number_format($service->price, 2) }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.editService', $service->id) }}" 
                                   class="btn btn-warning btn-sm"
                                   title="Edit Service">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.deleteService', $service->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this service?')"
                                            title="Delete Service">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>