<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand</title>
    <link rel="stylesheet" href="{{ asset('css/brands/index.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @include('layouts.navigation')
    <div class="container">
        <h1>Danh sách Thương Hiệu</h1>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createBrandModal"><i class="bi bi-plus"></i> Thêm Thương Hiệu Mới</button>

        <!-- Modal tạo mới thương hiệu -->
        <div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createBrandModalLabel">Thêm Thương Hiệu Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createBrandForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên Thương Hiệu</label>
                                <input type="text" name="name" id="brandName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Mô Tả</label>
                                <textarea name="description" id="brandDescription" class="form-control"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="saveBrandBtn">Lưu Thương Hiệu</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal chỉnh sửa thương hiệu -->
        <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editBrandModalLabel">Sửa Thương Hiệu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editBrandForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="editBrandName">Tên Thương Hiệu</label>
                                <input type="text" name="name" id="editBrandName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editBrandDescription">Mô Tả</label>
                                <textarea name="description" id="editBrandDescription" class="form-control"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" id="updateBrandBtn">Cập Nhật Thương Hiệu</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th class="col-1">#</th>
                    <th class="col-2">Tên Thương Hiệu</th>
                    <th class="col-7">Mô Tả</th>
                    <th class="col-2">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                <tr id="brand-row-{{ $brand->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>{{ $brand->description }}</td>
                    <td>
                        <button class="btn btn-outline-info btn-edit" data-id="{{ $brand->id }}">Sửa</button>
                        <button class="btn btn-outline-danger btn-delete" data-id="{{ $brand->id }}">Xóa</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Quay lại Dashboard</a>
    </div>

    <script>
        // Hàm xác nhận xóa
        function confirmDelete(event) {
            if (!confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?')) {
                event.preventDefault();
            }
        }

        // Lưu Thương Hiệu mới
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
                    if(response.success){
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
                    if(xhr.status === 422){
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function(key, value){
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
                    if(response.success){
                        var row = $('#brand-row-' + brandId);
                        row.find('td:nth-child(2)').text(response.brand.name);
                        row.find('td:nth-child(3)').text(response.brand.description);
                        $('#editBrandModal').modal('hide');
                        form[0].reset();
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 422){
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function(key, value){
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

            if(confirm('Bạn có chắc chắn muốn xóa thương hiệu này không?')) {
                $.ajax({
                    url: '/brands/' + brandId,
                    type: 'DELETE',
                    data: {
                        _token: $('input[name="_token"]').val(),
                    },
                    success: function(response) {
                        if(response.success){
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
</body>
</html>
