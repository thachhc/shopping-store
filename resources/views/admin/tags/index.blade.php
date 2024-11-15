<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tags</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/tags/index.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navigation')
    <div class="container">
        <h1>Tags list</h1>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createTagModal"><i class="bi bi-plus"></i> Add New Tag</button>
        <div class="modal fade" id="createTagModal" tabindex="-1" aria-labelledby="createTagModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTagModalLabel">Add New Tag</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createTagForm">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tag Name</label>
                                <input type="text" name="name" id="tagName" class="form-control" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="saveTagBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editTagModal" tabindex="-1" aria-labelledby="editTagModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTagModalLabel">Edit Tag</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editTagForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="editTagName">Tag Name</label>
                                <input type="text" name="name" id="editTagName" class="form-control" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="updateTagBtn">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table sm">
            <thead>
                <tr>
                    <th class="col-1">#</th>
                    <th class="col-7">Tag Name</th>
                    <th class="col-4">Operation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tags as $tag)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tag->name }}</td>
                    <td>
                        <button class="btn btn-outline-primary btn-edit" data-id="{{ $tag->id }}">Edit</button>
                        <!-- Form xóa danh mục -->
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;" onsubmit="confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Return Dashboard</a>
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

    $('#saveTagBtn').click(function() {
        let name = $('#tagName').val();

        if (name === '') {
            alert('Vui lòng nhập tên danh mục!');
            return;
        }

        $.ajax({
            url: "{{ route('tags.store') }}",
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
                                <button class="btn btn-outline-primary btn-edit" data-id="${response.id}">Edit</button>
                                <form action="{{ route('tags.destroy', '') }}/${response.id}" method="POST" style="display:inline;" onsubmit="confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                $('table tbody').append(newRow);
                // Đóng modal
                $('#createTagModal').modal('hide');
                // Reset form
                $('#createTagForm')[0].reset();
            },
            error: function() {
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            }
        });
    });



    $(document).ready(function() {
        // Hiển thị modal khi nhấn nút sửa
        $('body').on('click', '.btn-edit', function() {
            var tagId = $(this).data('id');

            // Gửi yêu cầu AJAX để lấy thông tin danh mục
            $.ajax({
                url: '/tags/' + tagId + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#editTagName').val(response.tag.name);
                    $('#editTagForm').attr('action', '/tags/' + tagId);
                    $('#editTagModal').modal('show');
                },
                error: function() {
                    alert('Không thể tải thông tin chỉnh sửa');
                }
            });
        });

        // Cập nhật danh mục khi nhấn Cập Nhật
        $('#updateTagBtn').click(function() {
            var tagId = $('#editTagForm').attr('action').split('/').pop();
            var tagName = $('#editTagName').val();

            if (tagName === '') {
                alert('Vui lòng nhập tên danh mục!');
                return;
            }

            $.ajax({
                url: '/tags/' + tagId,
                type: 'POST',
                data: {
                    _method: 'PUT',
                    _token: $('input[name="_token"]').val(),
                    name: tagName
                },
                success: function(response) {
                    var row = $('button[data-id="' + tagId + '"]').closest('tr');
                    row.find('td:nth-child(2)').text(response.tag.name);
                    $('#editTagModal').modal('hide');
                    $('#editTagForm')[0].reset();
                },
                error: function() {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            });
        });
    });
</script>
