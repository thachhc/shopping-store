<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List product') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <h1 class="text-center">Product List</h1>
        <div class="mb-4">
            <form action="{{ route('products.search') }}" method="GET" class="form-inline d-flex justify-content-center">
                <input type="text" name="search" class="col-md-6 search" placeholder="Search by product name"
                    value="{{ request()->query('search') }}">
                <button type="submit" class="btn btn-outline-info col-1 btnsearch">Search</button>
            </form>
        </div>
        <!-- Add New Product button -->
        <div class="text-right mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-outline-primary">Add New Product</a>
        </div>

        <table class="table sm">
            <thead class="thead-light">
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Tag</th>
                    <th>Price</th>
                    <th>Price Sale</th>
                    <th>Status</th>
                    <th>Images</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->brand->name }}</td>
                        <td>{{ $product->tag->name }}</td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                        <td>{{ number_format($product->price_sale, 0, ',', '.') }} VND</td>
                        <td>{{ $product->status ? 'In Stock' : 'Out of Stock' }}</td>
                        <td>
                            @php
                                $images = json_decode($product->image, true) ?? [];
                            @endphp
                            @if (is_array($images) && !empty($images))
                                {{-- Lấy giá trị của hình ảnh đầu tiên --}}
                                <img src="{{ asset(reset($images)) }}" alt="Product Image" class="img-thumbnail mb-2"
                                    width="100">
                            @else
                                <p>No images available</p>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('products.edit', $product->id) }}"
                                class="btn btn-outline-primary">Update</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center mt-4">
            <form action="{{ route('admin.dashboard') }}" method="GET" style="display:inline;">
                <button type="submit" class="btn btn-outline-secondary">Return to Dashboard</button>
            </form>
        </div>
    </div>

</x-app-layout>
