<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Category Manage</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" >
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="/css/admin/category.css">
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
                                    <i class="fa-solid fa-box"></i> Product
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white active" href="#">
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
                                <a class="nav-link text-white" href="{{ route('orders.index') }}">
                                    <i class="fa-solid fa-cart-shopping"></i> Order
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Category Manage</h1>
                    </div>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal"><i class="bi bi-plus"></i> Add New Category</button>
                    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createCategoryModalLabel">New Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="createCategoryForm">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Category</label>
                                            <input type="text" name="name" id="categoryName" class="form-control" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editCategoryForm" action="" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="editCategoryName">Category</label>
                                            <input type="text" name="name" id="editCategoryName" class="form-control"
                                                required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="updateCategoryBtn">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table sm">
                        <thead>
                            <tr>
                                <th class="col-1" style="text-align: center; vertical-align: middle;">#</th>
                                <th class="col-9" style="text-align: center; vertical-align: middle;">Category</th>
                                <th class="col-4" style="text-align: center; vertical-align: middle;">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                <td style="text-align: center; vertical-align: middle;">{{ $category->name }}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <button class="btn btn-outline-primary btn-edit" data-id="{{ $category->id }}">Update</button>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        style="display:inline;" onsubmit="confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                                    </form>
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
    // Hàm xác nhận xóa
    function confirmDelete(event) {
        // Hiển thị hộp thoại xác nhận
        if (!confirm('Bạn có chắc chắn muốn xóa danh mục này không?')) {
            event.preventDefault();
        }
    }

    $('#saveCategoryBtn').click(function() {
        let name = $('#categoryName').val();

        if (name === '') {
            alert('Vui lòng nhập tên danh mục!');
            return;
        }

        $.ajax({
            url: "{{ route('categories.store') }}",
            type: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                name: name
            },
            success: function(response) {
                let currentRowCount = $('table tbody tr').length + 1;
                // Thêm danh mục mới vào bảng
                let newRow = `
                        <tr>
                            <td>${currentRowCount}</td>
                            <td>${response.name}</td>
                            <td>
                                <button class="btn btn-outline-info btn-edit" data-id="${response.id}">Sửa</button>
                                <form action="{{ route('categories.destroy', '') }}/${response.id}" method="POST" style="display:inline;" onsubmit="confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    `;
                $('table tbody').append(newRow);
                // Đóng modal
                $('#createCategoryModal').modal('hide');
                // Reset form
                $('#createCategoryForm')[0].reset();
            },
            error: function() {
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            }
        });
    });



    $(document).ready(function() {
        // Hiển thị modal khi nhấn nút sửa
        $('body').on('click', '.btn-edit', function() {
            var categoryId = $(this).data('id');

            // Gửi yêu cầu AJAX để lấy thông tin danh mục
            $.ajax({
                url: '/categories/' + categoryId + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#editCategoryName').val(response.category.name);
                    $('#editCategoryForm').attr('action', '/categories/' + categoryId);
                    $('#editCategoryModal').modal('show');
                },
                error: function() {
                    alert('Không thể tải thông tin chỉnh sửa');
                }
            });
        });

        // Cập nhật danh mục khi nhấn Cập Nhật
        $('#updateCategoryBtn').click(function() {
            var categoryId = $('#editCategoryForm').attr('action').split('/').pop();
            var categoryName = $('#editCategoryName').val();

            if (categoryName === '') {
                alert('Vui lòng nhập tên danh mục!');
                return;
            }

            $.ajax({
                url: '/categories/' + categoryId,
                type: 'POST',
                data: {
                    _method: 'PUT',
                    _token: $('input[name="_token"]').val(),
                    name: categoryName
                },
                success: function(response) {
                    var row = $('button[data-id="' + categoryId + '"]').closest('tr');
                    row.find('td:nth-child(2)').text(response.category.name);
                    $('#editCategoryModal').modal('hide');
                    $('#editCategoryForm')[0].reset();
                },
                error: function() {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            });
        });
    });
</script>