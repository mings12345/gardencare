<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Homeowners</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Homeowners</h1>

        <!-- Add Homeowner Button -->
        <a href="{{ route('admin.addHomeowner') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Add Homeowner
        </a>

        <!-- Homeowners Table -->
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
                @foreach ($homeowners as $homeowner)
                <tr>
                    <td>{{ $homeowner->id }}</td>
                    <td>{{ $homeowner->name }}</td>
                    <td>{{ $homeowner->email }}</td>
                    <td>{{ $homeowner->phone }}</td>
                    <td>{{ $homeowner->address }}</td>
                    <td>
                        <!-- View Button -->
                        <a href="{{ route('admin.viewHomeowner', $homeowner->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>

                        <!-- Edit Button -->
                        <a href="{{ route('admin.editHomeowner', $homeowner->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.deleteHomeowner', $homeowner->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this homeowner?')">
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