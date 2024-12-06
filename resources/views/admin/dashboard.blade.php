<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="container mt-3">
       
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Products</h5>
                        <p class="card-text">Add, edit, delete products.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Go to</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Categories</h5>
                        <p class="card-text">Add, edit, delete categories.</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-success">Go to</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Brands</h5>
                        <p class="card-text">Add, edit, delete brands.</p>
                        <a href="{{ route('brands.index') }}" class="btn btn-info">Go to</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Tags</h5>
                        <p class="card-text">Add, edit, delete tags.</p>
                        <a href="{{ route('tags.index') }}" class="btn btn-warning">Go to</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Orders</h5>
                        <p class="card-text">Add, edit, delete orders.</p>
                        <a href="{{ route('tags.index') }}" class="btn btn-warning">Go to</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage User</h5>
                        <p class="card-text">Add, edit, delete user.</p>
                        <a href="{{ route('tags.index') }}" class="btn btn-warning">Go to</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
