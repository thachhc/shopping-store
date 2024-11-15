<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="stylesheet" href="{{ asset('css/categories/index.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navigation')
    <div class="container">
        <h1>Danh sách Danh Mục</h1>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal"><i
                class="bi bi-plus"></i> Thêm Danh Mục Mới</button>
        <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCategoryModalLabel">Thêm Danh Mục Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createCategoryForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên Danh Mục</label>
                                <input type="text" name="name" id="categoryName" class="form-control" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="saveCategoryBtn">Lưu Danh Mục</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Sửa Danh Mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCategoryForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="editCategoryName">Tên Danh Mục</label>
                                <input type="text" name="name" id="editCategoryName" class="form-control"
                                    required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="updateCategoryBtn">Cập Nhật Danh Mục</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table sm">
            <thead>
                <tr>
                    <th class="col-1">#</th>
                    <th class="col-7">Tên Danh Mục</th>
                    <th class="col-4">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <button class="btn btn-outline-info btn-edit" data-id="{{ $category->id }}">Sửa</button>
                            <!-- Form xóa danh mục -->
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                style="display:inline;" onsubmit="confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Quay lại Dashboard</a>
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
