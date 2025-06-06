<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Gardener | GardenCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --greenspace-primary: #2e7d32;
            --greenspace-secondary: #81c784;
            --greenspace-light: #e8f5e9;
        }
        body {
            background-color: #f5f9f5;
        }
        .greenspace-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
            overflow: hidden;
        }
        .greenspace-card-header {
            background-color: var(--greenspace-primary);
            color: white;
            padding: 1.5rem;
        }
        .detail-item {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .btn-greenspace {
            background-color: var(--greenspace-primary);
            color: white;
            border: none;
        }
        .btn-greenspace:hover {
            background-color: #1b5e20;
        }
        .detail-icon {
            color: var(--greenspace-primary);
            margin-right: 10px;
        }
        .service-card {
            border-left: 4px solid var(--greenspace-primary);
            margin-bottom: 1rem;
        }
        .service-type-badge {
            background-color: var(--greenspace-secondary);
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="greenspace-card">
            <div class="greenspace-card-header">
                <h1 class="h4 mb-0"><i class="bi bi-flower2"></i> Gardener Details</h1>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="detail-item">
                            <h5 class="d-flex align-items-center">
                                <i class="bi bi-person-fill detail-icon"></i>
                                {{ $gardener->name }}
                            </h5>
                        </div>
                        <div class="detail-item">
                            <p class="d-flex align-items-center">
                                <i class="bi bi-envelope-fill detail-icon"></i>
                                <strong>Email:</strong> &nbsp;{{ $gardener->email }}
                            </p>
                        </div>
                        <div class="detail-item">
                            <p class="d-flex align-items-center">
                                <i class="bi bi-telephone-fill detail-icon"></i>
                                <strong>Phone:</strong> &nbsp;{{ $gardener->phone }}
                            </p>
                        </div>
                        <div class="detail-item">
                            <p class="d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill detail-icon"></i>
                                <strong>Address:</strong> &nbsp;{{ $gardener->address }}
                            </p>
                        </div>
                       
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('admin.manageGardeners') }}" class="btn btn-greenspace px-4">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>