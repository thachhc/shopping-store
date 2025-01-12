<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/admin/product.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            <a class="nav-link text-white active" href="#">
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
                            <a class="nav-link text-white" href="{{ route('orders.index') }}">
                                <i class="fa-solid fa-cart-shopping"></i> Order
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Product Manage</h1>
                </div>
                <div class="mb-4">
                    <div class="text-left mb-3">
                        <a href="{{ route('products.create') }}" class="btn btn-outline-primary">Add New Product</a>
                    </div>
                    <form action="{{ route('products.search') }}" method="GET" class="form-inline d-flex justify-content-center">
                        <input type="text" name="search" class="col-md-6 search" placeholder="Search by product name" value="{{ request()->query('search') }}">
                        <button type="submit" class="btn btn-primary ms-2 btnsearch">Search</button>
                    </form>
                </div>
                <table class="table sm">
                    <thead class="thead-light">
                        <tr>
                            <th class="col-1" style="text-align: center; vertical-align: middle;">#</th>
                            <th class="col-2" style="text-align: center; vertical-align: middle;">Product Name</th>
                            <th class="col-1" style="text-align: center; vertical-align: middle;">Category</th>
                            <th class="col-1" style="text-align: center; vertical-align: middle;">Brand</th>
                            <th class="col-1" style="text-align: center; vertical-align: middle;">Tag</th>
                            <th class="col-1" style="text-align: center; vertical-align: middle;">Price</th>
                            <th class="col-1" style="text-align: center; vertical-align: middle;">Price Sale</th>
                            <th class="col-1" style="text-align: center; vertical-align: middle;">Status</th>
                            <th class="col-1" style="text-align: center; vertical-align: middle;">Images</th>
                            <th class="col-4" style="text-align: center; vertical-align: middle;">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr> 
                            <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $product->name }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $product->category->name }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $product->brand->name }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $product->tag->name }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ number_format($product->price, 0, ',', '.') }} </td>
                            <td style="text-align: center; vertical-align: middle;">{{ number_format($product->price_sale, 0, ',', '.') }} </td>
                            <td style="text-align: center; vertical-align: middle;">{{ $product->status ? 'In Stock' : 'Out of Stock' }}</td>
                            <td style="text-align: center; vertical-align: middle;">
                                @php
                                $images = json_decode($product->image, true) ?? [];
                                @endphp
                                @if(is_array($images) && !empty($images))
                                {{-- Lấy giá trị của hình ảnh đầu tiên --}}
                                <img src="{{ asset(reset($images)) }}" alt="Product Image" class="img-thumbnail mb-2" width="100">
                                @else
                                <p>No images available</p>
                                @endif
                            </td>

                            <td style="text-align: center; vertical-align: middle;">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary">Update</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
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