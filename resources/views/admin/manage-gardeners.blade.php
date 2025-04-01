<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gardeners</title>
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
            <h1>Manage Gardeners</h1>
            <div>
                <a href="{{ route('admin.addGardener') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Gardener
                </a>
            </div>
        </div>

        <!-- Success Message (if any) -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($gardeners->isEmpty())
            <div class="alert alert-info">No gardeners found.</div>
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
                        @foreach ($gardeners as $gardener)
                        <tr>
                            <td>{{ $gardener->id }}</td>
                            <td>{{ $gardener->name }}</td>
                            <td>{{ $gardener->email }}</td>
                            <td>{{ $gardener->phone ?? 'N/A' }}</td>
                            <td>{{ $gardener->address ?? 'N/A' }}</td>
                            <td class="action-buttons">
                                <!-- View Action -->
                                <a href="{{ route('admin.viewGardener', $gardener->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <!-- Edit Action -->
                                <a href="{{ route('admin.editGardener', $gardener->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Delete Action -->
                                <form action="{{ route('admin.deleteGardener', $gardener->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this gardener?')">
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