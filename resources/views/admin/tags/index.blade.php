<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tag Manage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/admin/tag.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@include('layouts.navigation')
<body>
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
                            <a class="nav-link text-white" href="{{ route('brands.index') }}">
                                <i class="fa-solid fa-bag-shopping"></i>  Brand
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="#">
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
                    <h1 class="h2">Tag Manage</h1>
                </div>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createTagModal"><i class="bi bi-plus"></i> Add New Tag</button>

                <!-- Modal Create Tag -->
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
                                        <label for="tagName">Tag Name</label>
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

                <!-- Modal Edit Tag -->
                <div class="modal fade" id="editTagModal" tabindex="-1" aria-labelledby="editTagModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editTagModalLabel">Edit Tag</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editTagForm">
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
                            <th class="col-1" style="text-align: center; vertical-align: middle;">#</th>
                            <th class="col-9" style="text-align: center; vertical-align: middle;">Tag Name</th>
                            <th class="col-4" style="text-align: center; vertical-align: middle;">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                        <tr id="tag-{{ $tag->id }}">
                            <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $tag->name }}</td>
                            <td style="text-align: center; vertical-align: middle;">
                                <button class="btn btn-outline-primary btn-edit" data-id="{{ $tag->id }}">Update</button>
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
            </main>
        </div>
    </div>
</body>

</html>

<script>
    function confirmDelete(event) {
        if (!confirm('Are you sure you want to delete this category?')) {
            event.preventDefault();
        }
    }

    $('#saveTagBtn').click(function() {
        let name = $('#tagName').val();

        if (name === '') {
            alert('Please enter a category name!');
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
                let newRow = `
                        <tr id="tag-${response.id}">
                            <td>${$('table tbody tr').length + 1}</td>
                            <td>${response.name}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-edit" data-id="${response.id}">Update</button>
                                <form action="{{ route('tags.destroy', '') }}/${response.id}" method="POST" style="display:inline;" onsubmit="confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                $('table tbody').append(newRow);
                $('#createTagModal').modal('hide');
                $('#createTagForm')[0].reset();
            },
            error: function() {
                alert('An error occurred. Please try again!');
            }
        });
    });

    // edit Tag
    $(document).on('click', '.btn-edit', function() {
        var tagId = $(this).data('id');

        $.ajax({
            url: '/tags/' + tagId + '/edit',
            type: 'GET',
            success: function(response) {
                $('#editTagName').val(response.tag.name);
                $('#editTagForm').attr('action', '/tags/' + tagId);
                $('#editTagModal').modal('show');
            },
            error: function() {
                alert('Unable to load edit information');
            }
        });
    });

    // Update Tag
    $('#updateTagBtn').click(function() {
        var tagId = $('#editTagForm').attr('action').split('/').pop();
        var tagName = $('#editTagName').val();

        if (tagName === '') {
            alert('Please enter a category name!');
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
                alert('An error occurred. Please try again!');
            }
        });
    });
</script>