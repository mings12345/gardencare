<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management | GardenCare</title>
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

        .container-fluid {
            padding: 30px;
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

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #FFF3CD;
            color: #856404;
        }

        .status-confirmed {
            background-color: #D4EDDA;
            color: #155724;
        }

        .status-completed {
            background-color: #D1ECF1;
            color: #0C5460;
        }

        .status-cancelled {
            background-color: #F8D7DA;
            color: #721C24;
        }

        .status-accepted {
            background-color: #D4EDDA;
            color: #155724;
        }

        .status-declined {
            background-color: #F8D7DA;
            color: #721C24;
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

        .badge-service {
            background-color: var(--light-color);
            color: var(--dark-color);
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }

        .badge-gardening {
            background-color: #4CAF50;
            color: white;
        }

        .badge-landscaping {
            background-color: #2196F3;
            color: white;
        }

        .payment-details {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
        }

        .payment-details strong {
            display: inline-block;
            width: 80px;
        }

        .payment-status-paid {
            color: #28a745;
            font-weight: bold;
        }

        .payment-status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .payment-status-failed {
            color: #dc3545;
            font-weight: bold;
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

        .empty-state p {
            color: var(--text-light);
            max-width: 500px;
            margin: 0 auto 25px;
        }

        .filter-section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        .filter-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .form-select, .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(139, 195, 74, 0.25);
        }

        .search-input {
            position: relative;
        }

        .search-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .search-input input {
            padding-left: 45px;
        }

        .clear-filters-btn {
            background-color: var(--accent-color);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .clear-filters-btn:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .results-info {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .results-count {
            font-weight: 500;
            color: var(--primary-color);
        }

        .date-range-container {
            display: flex;
            gap: 10px;
        }

        .date-range-item {
            flex: 1;
        }

        @media (max-width: 992px) {
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
            }

            .results-info {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .date-range-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="fas fa-calendar-alt me-2"></i> Bookings Management</h1>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h5 class="filter-title"><i class="fas fa-filter me-2"></i>Filter & Search Bookings</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="searchInput" class="form-label">Search by Booking ID</label>
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control" id="searchInput" placeholder="Enter Booking ID (e.g., 25-01-01)">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date Range</label>
                    <div class="date-range-container">
                        <div class="date-range-item">
                            <input type="date" class="form-control" id="startDateFilter">
                        </div>
                        <div class="date-range-item">
                            <input type="date" class="form-control" id="endDateFilter">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="accepted">Accepted</option>
                        <option value="completed">Completed</option>
                        <option value="declined">Declined</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="typeFilter" class="form-label">Service Type</label>
                    <select class="form-select" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="gardening">Gardening</option>
                        <option value="landscaping">Landscaping</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="button" class="btn clear-filters-btn w-100" id="clearFilters">
                            <i class="fas fa-times me-2"></i>Clear All Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Info -->
        <div class="results-info" id="resultsInfo">
            <span class="results-count" id="resultsCount">Showing all bookings</span>
            <small class="text-muted" id="filterStatus"></small>
        </div>

        <!-- Sample Data for Demonstration -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Type</th>
                        <th>Client</th>
                        <th>Professional</th>
                        <th>Date & Time</th>
                        <th>Services</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead> 
                <tbody id="bookingsTableBody">
                    <!-- Sample Gardening Bookings -->
                    <tr data-booking-id="25-01-01" 
                        data-booking-date="2025-06-15" 
                        data-created-date="2025-06-01T10:30:00">
                        <td data-label="ID">25-01-01</td>
                        <td data-label="Type">
                            <span class="badge badge-gardening">Gardening</span>
                        </td>
                        <td data-label="Homeowner">
                            <strong>John Smith</strong>
                            <div class="text-muted small">123 Garden Ave, Cebu City</div>
                        </td>
                        <td data-label="Professional">
                            <span class="badge bg-success">Gardener</span>
                            Maria Santos
                        </td>
                        <td data-label="Date & Time">
                            <strong>Jun 15, 2025</strong>
                            <div class="text-muted small">2:00 PM</div>
                        </td>
                        <td data-label="Services">
                            <span class="badge badge-service">Lawn Mowing</span>
                            <span class="badge badge-service">Pruning</span>
                        </td>
                        <td data-label="Payment">
                            <div class="payment-details mb-2">
                                <div>
                                    <strong>Amount:</strong> 
                                    ₱1,500.00
                                </div>
                                <div>
                                    <strong>Status:</strong> 
                                    <span class="payment-status-paid">Paid</span>
                                </div>
                                <div>
                                    <strong>Date:</strong> 
                                    Jun 01, 2025
                                </div>
                                <div>
                                    <strong>Admin Fee:</strong> 
                                    ₱150.00
                                </div>
                            </div>
                        </td>
                        <td data-label="Total">₱1,500.00</td>
                        <td data-label="Status">
                            <span class="status-badge status-accepted">Accepted</span>
                        </td>
                        <td data-label="Created">
                            <strong>Jun 01, 2025</strong>
                            <div class="text-muted small">10:30 AM</div>
                        </td>
                    </tr>

                    <tr data-booking-id="25-01-02" 
                        data-booking-date="2025-06-20" 
                        data-created-date="2025-06-02T14:15:00">
                        <td data-label="ID">25-01-02</td>
                        <td data-label="Type">
                            <span class="badge badge-gardening">Gardening</span>
                        </td>
                        <td data-label="Homeowner">
                            <strong>Lisa Chen</strong>
                            <div class="text-muted small">456 Rose Street, Cebu City</div>
                        </td>
                        <td data-label="Professional">
                            <span class="badge bg-success">Gardener</span>
                            Carlos Rivera
                        </td>
                        <td data-label="Date & Time">
                            <strong>Jun 20, 2025</strong>
                            <div class="text-muted small">9:00 AM</div>
                        </td>
                        <td data-label="Services">
                            <span class="badge badge-service">Weeding</span>
                            <span class="badge badge-service">Fertilizing</span>
                        </td>
                        <td data-label="Payment">
                            <div class="payment-details mb-2">
                                <div>
                                    <strong>Amount:</strong> 
                                    ₱1,200.00
                                </div>
                                <div>
                                    <strong>Status:</strong> 
                                    <span class="payment-status-pending">Pending</span>
                                </div>
                                <div>
                                    <strong>Date:</strong> 
                                    Jun 02, 2025
                                </div>
                            </div>
                        </td>
                        <td data-label="Total">₱1,200.00</td>
                        <td data-label="Status">
                            <span class="status-badge status-pending">Pending</span>
                        </td>
                        <td data-label="Created">
                            <strong>Jun 02, 2025</strong>
                            <div class="text-muted small">2:15 PM</div>
                        </td>
                    </tr>

                    <!-- Sample Landscaping Bookings -->
                    <tr data-booking-id="25-02-01" 
                        data-booking-date="2025-06-25" 
                        data-created-date="2025-06-03T09:45:00">
                        <td data-label="ID">25-02-01</td>
                        <td data-label="Type">
                            <span class="badge badge-landscaping">Landscaping</span>
                        </td>
                        <td data-label="Homeowner">
                            <strong>Robert Johnson</strong>
                            <div class="text-muted small">789 Landscape Drive, Cebu City</div>
                        </td>
                        <td data-label="Professional">
                            <span class="badge bg-info">Service Provider</span>
                            Green Valley Landscaping
                        </td>
                        <td data-label="Date & Time">
                            <strong>Jun 25, 2025</strong>
                            <div class="text-muted small">8:00 AM</div>
                        </td>
                        <td data-label="Services">
                            <span class="badge badge-service">Garden Design</span>
                            <span class="badge badge-service">Plant Installation</span>
                        </td>
                        <td data-label="Payment">
                            <div class="payment-details mb-2">
                                <div>
                                    <strong>Amount:</strong> 
                                    ₱5,000.00
                                </div>
                                <div>
                                    <strong>Status:</strong> 
                                    <span class="payment-status-paid">Paid</span>
                                </div>
                                <div>
                                    <strong>Date:</strong> 
                                    Jun 03, 2025
                                </div>
                                <div>
                                    <strong>Admin Fee:</strong> 
                                    ₱500.00
                                </div>
                            </div>
                        </td>
                        <td data-label="Total">₱5,000.00</td>
                        <td data-label="Status">
                            <span class="status-badge status-completed">Completed</span>
                        </td>
                        <td data-label="Created">
                            <strong>Jun 03, 2025</strong>
                            <div class="text-muted small">9:45 AM</div>
                        </td>
                    </tr>

                    <tr data-booking-id="25-02-03" 
                        data-booking-date="2025-06-30" 
                        data-created-date="2025-06-05T11:20:00">
                        <td data-label="ID">25-02-03</td>
                        <td data-label="Type">
                            <span class="badge badge-landscaping">Landscaping</span>
                        </td>
                        <td data-label="Homeowner">
                            <strong>Sarah Williams</strong>
                            <div class="text-muted small">321 Park View, Cebu City</div>
                        </td>
                        <td data-label="Professional">
                            <span class="badge bg-info">Service Provider</span>
                            Nature's Touch Design
                        </td>
                        <td data-label="Date & Time">
                            <strong>Jun 30, 2025</strong>
                            <div class="text-muted small">10:00 AM</div>
                        </td>
                        <td data-label="Services">
                            <span class="badge badge-service">Hardscaping</span>
                            <span class="badge badge-service">Water Features</span>
                        </td>
                        <td data-label="Payment">
                            <span class="text-muted">No payments</span>
                        </td>
                        <td data-label="Total">₱8,500.00</td>
                        <td data-label="Status">
                            <span class="status-badge status-declined">Declined</span>
                        </td>
                        <td data-label="Created">
                            <strong>Jun 05, 2025</strong>
                            <div class="text-muted small">11:20 AM</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const startDateFilter = document.getElementById('startDateFilter');
            const endDateFilter = document.getElementById('endDateFilter');
            const statusFilter = document.getElementById('statusFilter');
            const typeFilter = document.getElementById('typeFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const resultsCount = document.getElementById('resultsCount');
            const filterStatus = document.getElementById('filterStatus');
            const tableRows = document.querySelectorAll('#bookingsTableBody tr');
            
            let totalBookings = tableRows.length;
            
            // Set default date range to current month
            const today = new Date();
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            startDateFilter.valueAsDate = firstDayOfMonth;
            endDateFilter.valueAsDate = lastDayOfMonth;
            
            function formatDateForDisplay(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            }
            
            function updateResultsInfo(visibleCount, activeFilters) {
                resultsCount.textContent = `Showing ${visibleCount} of ${totalBookings} bookings`;
                
                if (activeFilters.length > 0) {
                    filterStatus.textContent = `Filtered by: ${activeFilters.join(', ')}`;
                } else {
                    filterStatus.textContent = '';
                }
            }
            
            function getActiveFilters() {
                const filters = [];
                
                if (searchInput.value.trim()) {
                    filters.push(`ID: "${searchInput.value.trim()}"`);
                }
                
                if (startDateFilter.value || endDateFilter.value) {
                    const start = startDateFilter.value ? formatDateForDisplay(startDateFilter.value) : 'Start';
                    const end = endDateFilter.value ? formatDateForDisplay(endDateFilter.value) : 'End';
                    filters.push(`Date: ${start} to ${end}`);
                }
                
                if (statusFilter.value) {
                    filters.push(`Status: ${statusFilter.options[statusFilter.selectedIndex].text}`);
                }
                
                if (typeFilter.value) {
                    filters.push(`Type: ${typeFilter.options[typeFilter.selectedIndex].text}`);
                }
                
                return filters;
            }
            
            function applyFilters() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                const startDate = startDateFilter.value;
                const endDate = endDateFilter.value;
                const selectedStatus = statusFilter.value;
                const selectedType = typeFilter.value;
                
                // Validate date range
                if (startDate && endDate && startDate > endDate) {
                    alert("End date cannot be before start date");
                    endDateFilter.value = startDate;
                    return;
                }
                
                let visibleCount = 0;
                
                tableRows.forEach(row => {
                    let showRow = true;
                    
                    // Search by Booking ID (format: 25-01-01)
                    if (searchTerm) {
                        const bookingId = row.getAttribute('data-booking-id').toLowerCase();
                        const idParts = searchTerm.split('-');
                        
                        if (idParts.length === 3) {
                            const [yearPart, typePart, homeownerPart] = idParts;
                            const actualId = bookingId;
                            const actualParts = actualId.split('-');
                            
                            if (actualParts.length === 3) {
                                const [actualYear, actualType, actualHomeowner] = actualParts;
                                
                                // Check year part (25)
                                if (yearPart && !actualYear.includes(yearPart)) {
                                    showRow = false;
                                }
                                
                                // Check service type (01 = gardening, 02 = landscaping)
                                if (typePart === '01' && !row.querySelector('.badge-gardening')) {
                                    showRow = false;
                                }
                                if (typePart === '02' && !row.querySelector('.badge-landscaping')) {
                                    showRow = false;
                                }
                                
                                // Check homeowner ID (last two digits)
                                if (homeownerPart && !actualHomeowner.includes(homeownerPart)) {
                                    showRow = false;
                                }
                            } else {
                                showRow = false;
                            }
                        } else {
                            // Simple text search fallback
                            if (!bookingId.includes(searchTerm)) {
                                showRow = false;
                            }
                        }
                    }
                    
                    // Filter by Date Range (booking date)
                    if ((startDate || endDate) && showRow) {
                        const bookingDateStr = row.getAttribute('data-booking-date');
                        if (bookingDateStr) {
                            const bookingDate = bookingDateStr.split(' ')[0]; // Get just the date part
                            
                            if (startDate && bookingDate < startDate) {
                                showRow = false;
                            }
                            if (endDate && bookingDate > endDate) {
                                showRow = false;
                            }
                        } else {
                            showRow = false;
                        }
                    }
                    
                    // Filter by Status
                    if (selectedStatus && showRow) {
                        const statusBadge = row.querySelector('.status-badge');
                        if (!statusBadge || !statusBadge.classList.contains(`status-${selectedStatus}`)) {
                            showRow = false;
                        }
                    }
                    
                    // Filter by Type
                    if (selectedType && showRow) {
                        const typeCell = row.querySelector('td:nth-child(2)');
                        if (!typeCell || !typeCell.textContent.toLowerCase().includes(selectedType)) {
                            showRow = false;
                        }
                    }
                    
                    // Show/hide row
                    row.style.display = showRow ? '' : 'none';
                    if (showRow) visibleCount++;
                });
                
                // Update results info
                const activeFilters = getActiveFilters();
                updateResultsInfo(visibleCount, activeFilters);
            }
            
            // Event listeners
            searchInput.addEventListener('input', applyFilters);
            startDateFilter.addEventListener('change', applyFilters);
            endDateFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            typeFilter.addEventListener('change', applyFilters);
            
            // Clear all filters
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                startDateFilter.valueAsDate = firstDayOfMonth;
                endDateFilter.valueAsDate = lastDayOfMonth;
                statusFilter.value = '';
                typeFilter.value = '';
                applyFilters();
            });
            
            // Initialize
            applyFilters();
        });
    </script>
</body>
</html>