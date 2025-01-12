<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Brand Manage</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="/css/admin/brand.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                    <i class="fa-solid fa-box"></i>  Product
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('categories.index') }}">
                                    <i class="fa-solid fa-tags"></i>  Category
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white active" href="#">
                                    <i class="fa-solid fa-bag-shopping"></i>  Brand
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('tags.index') }}">
                                    <i class="fa-solid fa-tag"></i>  Tag
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('orders.index') }}">
                                    <i class="fa-solid fa-cart-shopping"></i>  Order
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Brand Manage</h1>
                    </div>
                    <button class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#createBrandModal"><i class="bi bi-plus"></i> Add New Brand</button>
                    <div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createBrandModalLabel">New Brand</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="createBrandForm">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="brandName" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="brandDescription" class="form-control"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="saveBrandBtn">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editBrandForm" action="" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="editBrandName">Name</label>
                                            <input type="text" name="name" id="editBrandName" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="editBrandDescription">Description</label>
                                            <textarea name="description" id="editBrandDescription" class="form-control"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="updateBrandBtn">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table sm">
                        <thead>
                            <tr>
                                <th class="col-1" style="text-align: center; vertical-align: middle;">#</th>
                                <th class="col-2" style="text-align: center; vertical-align: middle;">Name</th>
                                <th class="col-7" style="text-align: center; vertical-align: middle;">Description</th>
                                <th class="col-2" style="text-align: center; vertical-align: middle;">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                            <tr id="brand-row-{{ $brand->id }}">
                                <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td style="text-align: center; vertical-align: middle;">{{ $brand->name }}</td>
                                <td style="text-align: center; vertical-align: middle;">{{ $brand->description }}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <button class="btn btn-outline-primary btn-edit" data-id="{{ $brand->id }}">Update</button>
                                    <button class="btn btn-outline-danger btn-delete" data-id="{{ $brand->id }}">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </main>
            </div>
        </div>
    </body>
</html>
<script>
        function confirmDelete(event) {
            if (!confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?')) {
                event.preventDefault();
            }
        }
        $('#saveBrandBtn').click(function() {
            let name = $('#brandName').val();
            let description = $('#brandDescription').val();

            if (name === '') {
                alert('Vui lòng nhập tên thương hiệu!');
                return;
            }

            $.ajax({
                url: "{{ route('brands.store') }}",
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    name: name,
                    description: description
                },
                success: function(response) {
                    if (response.success) {
                        let newRow = `
                            <tr id="brand-row-${response.brand.id}">
                                <td>${$('table tbody tr').length + 1}</td>
                                <td>${response.brand.name}</td>
                                <td>${response.brand.description}</td>
                                <td>
                                    <button class="btn btn-outline-info btn-edit" data-id="${response.brand.id}">Sửa</button>
                                    <button class="btn btn-outline-danger btn-delete" data-id="${response.brand.id}">Xóa</button>
                                </td>
                            </tr>
                        `;
                        $('table tbody').append(newRow);
                        $('#createBrandModal').modal('hide');
                        $('#createBrandForm')[0].reset();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert('Có lỗi xảy ra. Vui lòng thử lại!');
                    }
                }
            });
        });

        // Hiển thị modal khi nhấn nút sửa
        $('body').on('click', '.btn-edit', function() {
            var brandId = $(this).data('id');
            // Gửi yêu cầu AJAX để lấy thông tin thương hiệu
            $.ajax({
                url: '/brands/' + brandId + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#editBrandName').val(response.brand.name);
                    $('#editBrandDescription').val(response.brand.description);
                    $('#editBrandForm').attr('action', '/brands/' + brandId);
                    $('#editBrandModal').modal('show');
                },
                error: function() {
                    alert('Không thể tải thông tin chỉnh sửa');
                }
            });
        });

        // Cập nhật Thương Hiệu
        $('#updateBrandBtn').click(function() {
            var form = $('#editBrandForm');
            var actionUrl = form.attr('action');
            var brandId = actionUrl.split('/').pop();
            var name = $('#editBrandName').val();
            var description = $('#editBrandDescription').val();

            if (name === '') {
                alert('Vui lòng nhập tên thương hiệu!');
                return;
            }

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: {
                    _method: 'PUT',
                    _token: $('input[name="_token"]').val(),
                    name: name,
                    description: description
                },
                success: function(response) {
                    if (response.success) {
                        var row = $('#brand-row-' + brandId);
                        row.find('td:nth-child(2)').text(response.brand.name);
                        row.find('td:nth-child(3)').text(response.brand.description);
                        $('#editBrandModal').modal('hide');
                        form[0].reset();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert('Có lỗi xảy ra. Vui lòng thử lại!');
                    }
                }
            });
        });

        // Xóa Thương Hiệu
        $('body').on('click', '.btn-delete', function() {
            var brandId = $(this).data('id');

            if (confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?')) {
                $.ajax({
                    url: '/brands/' + brandId,
                    type: 'DELETE',
                    data: {
                        _token: $('input[name="_token"]').val(),
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#brand-row-' + brandId).remove();
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra. Vui lòng thử lại!');
                    }
                });
            }
        });
    </script>