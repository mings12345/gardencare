<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Service Provider | GardenCare</title>
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
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(46, 125, 50, 0.08);
        }
        .service-type-badge {
            background-color: var(--greenspace-secondary);
            color: #000;
        }
        .service-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }
        .price-tag {
            color: var(--greenspace-primary);
            font-weight: bold;
            font-size: 1.1em;
        }
        .services-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--greenspace-light);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="greenspace-card">
            <div class="greenspace-card-header">
                <h1 class="h4 mb-0"><i class="bi bi-tools"></i> Service Provider Details</h1>
            </div>
            <div class="card-body p-4">
                 <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="detail-item">
                            <h5 class="d-flex align-items-center">
                                <i class="bi bi-person-fill detail-icon"></i>
                                {{ $serviceProvider->name }}
                            </h5>
                        </div>
                        <div class="detail-item">
                            <p class="d-flex align-items-center">
                                <i class="bi bi-envelope-fill detail-icon"></i>
                                <strong>Email:</strong> &nbsp;{{ $serviceProvider->email }}
                            </p>
                        </div>
                        <div class="detail-item">
                            <p class="d-flex align-items-center">
                                <i class="bi bi-telephone-fill detail-icon"></i>
                                <strong>Phone:</strong> &nbsp;{{ $serviceProvider->phone }}
                            </p>
                        </div>
                        <div class="detail-item">
                            <p class="d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill detail-icon"></i>
                                <strong>Address:</strong> &nbsp;{{ $serviceProvider->address }}
                            </p>
                        </div>
                    </div>
                </div>

                @if(isset($services) && $services->count() > 0)
                <div class="services-section">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <h5 class="mb-4 d-flex align-items-center">
                                <i class="bi bi-gear-fill detail-icon"></i>
                                Services Offered ({{ $services->count() }})
                            </h5>
                            
                            @foreach($services as $service)
                            <div class="service-card p-3 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        @if($service->image)
                                            <img src="{{ $service->image }}" alt="{{ $service->name }}" class="service-image">
                                        @else
                                            <div class="service-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-7">
                                        <h6 class="mb-2">{{ $service->name }}</h6>
                                        <span class="badge service-type-badge mb-2">{{ $service->type }}</span>
                                        @if($service->description)
                                            <p class="text-muted mb-0 small">{{ $service->description }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div class="price-tag">${{ number_format($service->price, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="services-section">
                    <div class="text-center text-muted">
                        <i class="bi bi-info-circle detail-icon"></i>
                        No services available for this service provider.
                    </div>
                </div>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('admin.manageServiceProviders') }}" class="btn btn-greenspace px-4">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>