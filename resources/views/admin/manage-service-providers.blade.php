<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Service Providers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Service Providers</h1>

        <!-- Success Message (if any) -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add Service Provider Button -->
        <a href="{{ route('admin.addServiceProvider') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Add Service Provider
        </a>

        <!-- Service Providers Table -->
        <table class="table table-bordered">
            <thead>
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
                    <td>{{ $serviceProvider->phone }}</td>
                    <td>{{ $serviceProvider->address }}</td>
                    <td>
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

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>