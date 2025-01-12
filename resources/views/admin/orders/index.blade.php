<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Manage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/admin/order.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navigation')
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar">
                <div class="position-sticky">
                    <div class="text-center py-3">
                        <h3 class="text-uppercase">Admin Panel</h3>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                                <i class="fa-solid fa-gauge"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('products.index') }}">
                                <i class="fa-solid fa-box"></i> Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('categories.index') }}">
                                <i class="fa-solid fa-tags"></i> Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('brands.index') }}">
                                <i class="fa-solid fa-bag-shopping"></i> Brand
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('tags.index') }}">
                                <i class="fa-solid fa-tag"></i> Tag
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#">
                                <i class="fa-solid fa-cart-shopping"></i> Order
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Order Manage</h1>
                </div>
                <!-- Form tìm kiếm -->
                <div class="mb-4">
                    <form action="{{ route('orders.search') }}" method="GET" class="form-inline d-flex justify-content-center">
                        <input type="text" class="col-md-6 search" name="customer_name" placeholder="Search by Customer Name" value="{{ request()->input('customer_name') }}">
                        <button type="submit" class="btn btn-primary ms-2">Search</button>
                    </form>
                </div>
                <!-- Bảng đơn hàng -->
                <div class="table-container">
                    <table class="table sm">
                        <thead>
                            <tr>
                                <th class="col-1 text-center">#</th>
                                <th class="col-1 text-center">ID Order</th>
                                <th class="col-2 text-center">Customer</th>
                                <th class="col-2 text-center">Total Amount</th>
                                <th class="col-1 text-center">Status</th>
                                <th class="col-2 text-center">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr id="order-row-{{ $order->id }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $order->id }}</td>
                                <td class="text-center">{{ $order->customer->name }}</td>
                                <td class="text-center">{{ $order->total_amount }}</td>
                                <td class="text-center">
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="form-select border border-primary rounded">
                                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Processed" {{ $order->status == 'Processed' ? 'selected' : '' }}>Processed</option>
                                            <option value="In Transit" {{ $order->status == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                                            <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-outline-primary btn-view" data-id="{{ $order->id }}">View Details</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal hiển thị chi tiết đơn hàng -->
                <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Order ID:</strong> <span id="order-id"></span></p>
                                <p><strong>Total Amount:</strong> <span id="order-amount"></span></p>
                                <p><strong>Customer:</strong> <span id="customer-name"></span></p>
                                <p><strong>Payment Method:</strong> <span id="payment-method"></span></p>

                                <!-- Table hiển thị thông tin sản phẩm -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Size</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-products">
                                        <!-- Các sản phẩm sẽ được thêm vào đây -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>



    <script>
        $(document).on('click', '.btn-view', function() {
            var orderId = $(this).data('id');

            $.ajax({
                url: `/orders/${orderId}/details`,
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        // Kiểm tra xem response.data.products có phải là mảng hay không
                        if (Array.isArray(response.data.products)) {
                            // Cập nhật thông tin đơn hàng vào modal
                            $('#order-id').text(orderId);
                            $('#order-amount').text(response.data.total_amount);
                            $('#customer-name').text(response.data.customer_name);
                            $('#payment-method').text(response.data.payment_method);

                            // Làm rỗng bảng sản phẩm trước khi thêm dữ liệu mới
                            $('#order-products').empty();

                            // Thêm từng sản phẩm vào bảng
                            response.data.products.forEach(product => {
                                $('#order-products').append(`
                            <tr>
                                <td>${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${product.size}</td>
                                <td>${product.price}</td>
                                <td>${product.total}</td>
                            </tr>
                        `);
                            });

                            // Hiển thị modal
                            $('#orderDetailsModal').modal('show');
                        } else {
                            alert('Sản phẩm không có dữ liệu hợp lệ.');
                        }
                    } else {
                        alert('Không thể lấy thông tin chi tiết đơn hàng.');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching order details:', xhr.responseText);
                    alert('Đã xảy ra lỗi khi lấy dữ liệu đơn hàng.');
                }
            });
        });
    </script>
</body>

</html>