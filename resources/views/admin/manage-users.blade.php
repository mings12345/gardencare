<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.2s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .card-text {
            font-size: 16px;
            color: #666;
        }

        .btn-action {
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>User Management</h1>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Total Homeowners Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Homeowners</h5>
                        <p class="card-text">{{ $totalHomeowners }}</p>
                        <a href="{{ route('admin.manageHomeowners') }}" class="btn btn-primary btn-action">
                            <i class="fas fa-edit"></i> Manage
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Gardeners Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Gardeners</h5>
                        <p class="card-text">{{ $totalGardeners }}</p>
                        <a href="{{ route('admin.manageGardeners') }}" class="btn btn-primary btn-action">
                            <i class="fas fa-edit"></i> Manage
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Service Providers Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Service Providers</h5>
                        <p class="card-text">{{ $totalServiceProviders }}</p>
                        <a href="{{ route('admin.manageServiceProviders') }}" class="btn btn-primary btn-action">
                            <i class="fas fa-edit"></i> Manage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>