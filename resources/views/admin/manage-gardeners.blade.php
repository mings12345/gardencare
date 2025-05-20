<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gardeners | GardenCare</title>
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
        }

        .container {
            padding: 30px;
            max-width: 1200px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .header-container h1 {
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
            font-size: 28px;
        }

        .btn-back {
            transition: var(--transition);
        }

        .btn-back:hover {
            transform: translateX(-3px);
        }

        .btn-add {
            transition: var(--transition);
        }

        .btn-add:hover {
            transform: translateY(-2px);
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            vertical-align: middle;
            padding: 15px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px 15px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(139, 195, 74, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-buttons .btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: var(--transition);
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .empty-state i {
            font-size: 50px;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .profile-image-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-color);
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .header-container h1 {
                margin-bottom: 0;
            }
            
            .action-buttons {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .table thead {
                display: none;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid #eee;
                border-radius: 8px;
                box-shadow: var(--shadow);
            }
            
            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--primary-color);
                margin-right: 15px;
                flex: 0 0 120px;
            }
            
            .table tbody td:last-child {
                border-bottom: none;
                justify-content: center;
            }

            .profile-image-cell {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .profile-image-cell::before {
                content: 'ID & Photo';
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Back Button and Title -->
        <div class="header-container">
            <div>
                <a href="{{ route('admin.manageUsers') }}" class="btn btn-outline-secondary btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
            </div>
            <h1><i class="fas fa-leaf me-2"></i> Manage Gardeners</h1>
            <div>
                <a href="{{ route('admin.addGardener') }}" class="btn btn-success btn-add">
                    <i class="fas fa-plus me-2"></i> Add Gardener
                </a>
            </div>
        </div>

        <!-- Success Message (if any) -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Gardeners Table -->
        @if($gardeners->isEmpty())
            <div class="empty-state">
                <i class="fas fa-leaf"></i>
                <h3>No Gardeners Found</h3>
                <p>There are currently no gardeners registered in the system.</p>
                <a href="{{ route('admin.addGardener') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i> Add Gardener
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID & Photo</th>
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
                            <td data-label="ID & Photo" class="profile-image-cell">
                                <span>{{ $gardener->id }}</span>
                                @if($gardener->profile_image)
                                    <img src="{{ asset('storage/' . $gardener->profile_image) }}" alt="Profile Image" class="profile-image">
                                @else
                                    <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile" class="profile-image">
                                @endif
                            </td>
                            <td data-label="Name">{{ $gardener->name }}</td>
                            <td data-label="Email">{{ $gardener->email }}</td>
                            <td data-label="Phone">{{ $gardener->phone ?? 'N/A' }}</td>
                            <td data-label="Address">{{ $gardener->address ?? 'N/A' }}</td>
                            <td data-label="Actions" class="action-buttons">
                                <!-- View Button -->
                                <a href="{{ route('admin.viewGardener', $gardener->id) }}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('admin.editGardener', $gardener->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.deleteGardener', $gardener->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this gardener?')">
                                        <i class="fas fa-trash"></i>
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

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>