<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Gardener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Gardener Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $gardener->name }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ $gardener->email }}</p>
                <p class="card-text"><strong>Phone:</strong> {{ $gardener->phone }}</p>
                <p class="card-text"><strong>Address:</strong> {{ $gardener->address }}</p>
                <!-- Corrected route name -->
                <a href="{{ route('admin.manageGardeners') }}" class="btn btn-primary">Back to List</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>