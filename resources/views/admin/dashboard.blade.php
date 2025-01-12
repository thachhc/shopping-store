<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/admin/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@include('layouts.navigation')

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar">
                <div class="position-sticky">
                    <div class="text-center py-3">
                        <h3 class="text-uppercase">Admin Panel</h3>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#">
                                <i class="fa-solid fa-gauge"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('products.index') }}">
                                <i class="fa-solid fa-box"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('categories.index') }}">
                                <i class="fa-solid fa-tags"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('brands.index') }}">
                                <i class="fa-solid fa-bag-shopping"></i> Brands
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('tags.index') }}">
                                <i class="fa-solid fa-tag"></i> Tags
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('orders.index') }}">
                                <i class="fa-solid fa-cart-shopping"></i> Orders
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <!-- Order Statistics -->
                <div class="mb-3">
                    <h1 class="h2">Order Statistics</h1>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="row align-items-center mb-4">
                        <label for="date_filter" class="col-md-2 col-form-label">Select Time Period:</label>
                        <div class="col-md-4">
                            <select name="date_filter" class="form-select" onchange="this.form.submit()">
                                <option value="1_day" {{ $dateFilter == '1_day' ? 'selected' : '' }}>Today</option>
                                <option value="3_days" {{ $dateFilter == '3_days' ? 'selected' : '' }}>Last 3 Days</option>
                                <option value="5_days" {{ $dateFilter == '5_days' ? 'selected' : '' }}>Last 5 Days</option>
                                <option value="7_days" {{ $dateFilter == '7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            </select>
                        </div>
                    </form>
                    <div class="row">
                        @foreach ($statuses as $status => $count)
                        <div class="col-md-3 mb-3">
                            <div class="card text-center border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-muted text-uppercase">{{ $status }}</h5>
                                    <p class="card-text">
                                        <span class="badge bg-primary fs-4">{{ $count }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="mb-5">
                    <h1 class="h2">Revenue Chart</h1>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="row align-items-center mb-4">
                        <label for="year_filter" class="col-md-2 col-form-label">Select Year:</label>
                        <div class="col-md-3">
                            <select name="year_filter" class="form-select" onchange="this.form.submit()">
                                <option value="">Select Year</option>
                                @foreach (range(2020, now()->year) as $year)
                                <option value="{{ $year }}" {{ $yearFilter == $year ? 'selected' : '' }}>Year {{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($yearFilter)
                        <label for="month_filter" class="col-md-2 col-form-label">Select Month:</label>
                        <div class="col-md-3">
                            <select name="month_filter" class="form-select" onchange="this.form.submit()">
                                <option value="">Select Month</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $monthFilter == $i ? 'selected' : '' }}>Month {{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        @endif
                    </form>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <canvas id="revenueChart" style="max-width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueData = @json($revenueData);
        const ctx = document.getElementById('revenueChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: revenueData.map(item => item.month || item.week),
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.map(item => item.revenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>