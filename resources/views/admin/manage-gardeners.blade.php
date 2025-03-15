<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gardeners</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Gardeners</h1>

        <!-- Success Message (if any) -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('admin.addGardener') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus"></i> Add Gardener
        </a>
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
                @foreach ($gardeners as $gardener)
                <tr>
                    <td>{{ $gardener->id }}</td>
                    <td>{{ $gardener->name }}</td>
                    <td>{{ $gardener->email }}</td>
                    <td>{{ $gardener->phone }}</td>
                    <td>{{ $gardener->address }}</td>
                    <td>
                        <!-- View Action -->
                        <a href="{{ route('admin.viewGardener', $gardener->id) }}" class="btn btn-sm btn-info">View</a>

                        <!-- Edit Action -->
                        <a href="{{ route('admin.editGardener', $gardener->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <!-- Delete Action -->
                        <form action="{{ route('admin.deleteGardener', $gardener->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this gardener?')">Delete</button>
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