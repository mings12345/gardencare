<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | GardenCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --accent-color: #8BC34A;
            --light-color: #F1F8E9;
            --dark-color: #1B5E20;
            --text-color: #333;
            --text-light: #666;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px;
            max-width: 1200px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .page-header h1 {
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
            font-size: 28px;
        }

        .back-button {
            margin-bottom: 30px;
            transition: var(--transition);
        }

        .back-button:hover {
            transform: translateX(-3px);
        }

        .card {
            transition: var(--transition);
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background-color: #fff;
            height: 100%;
            border-top: 4px solid var(--primary-color);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 25px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-icon {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 15px 0;
        }

        .btn-action {
            margin-top: 15px;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .card-homeowners {
            border-top-color: #4CAF50;
        }

        .card-gardeners {
            border-top-color: #2196F3;
        }

        .card-providers {
            border-top-color: #9C27B0;
        }

        .stats-section {
            margin-bottom: 40px;
        }

        @media (max-width: 768px) {
            .card {
                margin-bottom: 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-header h1 {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary back-button">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
        
        <div class="page-header">
            <h1><i class="fas fa-users me-2"></i> User Management</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="row stats-section">
            <!-- Total Homeowners Card -->
            <div class="col-md-4">
                <div class="card card-homeowners">
                    <div class="card-body">
                        <i class="fas fa-home card-icon"></i>
                        <h5 class="card-title">Homeowners</h5>
                        <div class="card-value">{{ $totalHomeowners }}</div>
                        <p class="text-muted">Registered property owners</p>
                        <a href="{{ route('admin.manageHomeowners') }}" class="btn btn-outline-primary btn-action">
                            <i class="fas fa-edit me-2"></i> Manage
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Gardeners Card -->
            <div class="col-md-4">
                <div class="card card-gardeners">
                    <div class="card-body">
                        <i class="fas fa-leaf card-icon"></i>
                        <h5 class="card-title">Gardeners</h5>
                        <div class="card-value">{{ $totalGardeners }}</div>
                        <p class="text-muted">Professional gardeners</p>
                        <a href="{{ route('admin.manageGardeners') }}" class="btn btn-outline-primary btn-action">
                            <i class="fas fa-edit me-2"></i> Manage
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Service Providers Card -->
            <div class="col-md-4">
                <div class="card card-providers">
                    <div class="card-body">
                        <i class="fas fa-tools card-icon"></i>
                        <h5 class="card-title">Service Providers</h5>
                        <div class="card-value">{{ $totalServiceProviders }}</div>
                        <p class="text-muted">Landscaping specialists</p>
                        <a href="{{ route('admin.manageServiceProviders') }}" class="btn btn-outline-primary btn-action">
                            <i class="fas fa-edit me-2"></i> Manage
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple animation for cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>