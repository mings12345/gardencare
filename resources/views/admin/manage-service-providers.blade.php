<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Service Providers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .action-buttons .btn:last-child {
            margin-right: 0;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Header with Back Button and Title -->
        <div class="header-container">
            <div>
                <a href="{{ route('admin.manageUsers') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
            <h1>Manage Service Providers</h1>
            <div>
                <a href="{{ route('admin.addServiceProvider') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Service Provider
                </a>
            </div>
        </div>

        <!-- Success Message (if any) -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($serviceProviders->isEmpty())
            <div class="alert alert-info">No service providers found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceProviders as $serviceProvider)
                        <tr>
                            <td>{{ $serviceProvider->id }}</td>
                            <td>{{ $serviceProvider->name }}</td>
                            <td>{{ $serviceProvider->email }}</td>
                            <td>{{ $serviceProvider->phone ?? 'N/A' }}</td>
                            <td>{{ $serviceProvider->address ?? 'N/A' }}</td>
                            <td class="action-buttons">
                                <!-- View Action -->
                                <a href="{{ route('admin.viewServiceProvider', $serviceProvider->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <!-- Edit Action -->
                                <a href="{{ route('admin.editServiceProvider', $serviceProvider->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Delete Action -->
                                <form action="{{ route('admin.deleteServiceProvider', $serviceProvider->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service provider?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>