<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .badge-gardening {
            background-color: #28a745;
            color: white;
        }
        .badge-landscaping {
            background-color: #17a2b8;
            color: white;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .action-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Header with Back Button and Title -->
        <div class="action-header">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            <h1>Service Management</h1>
            <div></div> <!-- Empty div for spacing balance -->
        </div>

        <!-- Success Message (if any) -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons mb-3">
            <a href="{{ route('admin.addService') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Add New Service
            </a>
        </div>

        <!-- Services Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->name }}</td>
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