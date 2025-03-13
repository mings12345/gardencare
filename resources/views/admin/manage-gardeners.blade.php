<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gardeners</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Gardeners</h1>

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
                        <a href="#" class="btn btn-sm btn-info">View</a>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>