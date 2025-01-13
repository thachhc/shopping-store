@extends('layouts.front')

@section('style')
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
    }

    h1 {
        font-size: 36px;
        font-weight: bold;
        color: #343a40;
        margin-bottom: 30px;
    }

    .product-detail-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 30px;
    }

    .product-images {
        flex: 1 1 45%;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .main-image {
        width: 100%;
        height: 400px;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .thumbs {
        display: flex;
        margin-top: 20px;
    }

    .thumbs img {
        width: 60px;
        height: 60px;
        margin-right: 10px;
        cursor: pointer;
        transition: transform 0.2s ease-in-out;
        border-radius: 8px;
    }

    .thumbs img:hover {
        transform: scale(1.1);
    }

    .product-info {
        flex: 1 1 50%;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .product-info h3 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #343a40;
    }

    .product-details p {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .actions {
        margin-top: 20px;
    }

    .buy-btn,
    .add-cart-btn {
        background-color: #007bff;
        color: #fff;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        margin-bottom: 10px;
    }

    .buy-btn:hover,
    .add-cart-btn:hover {
        background-color: #0056b3;
    }

    .size-options {
        margin-top: 20px;
    }

    .size-btn {
        background-color: #f8f9fa;
        border: 2px solid #007bff;
        color: #007bff;
        padding: 10px 20px;
        margin: 5px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .size-btn.active {
        background-color: #007bff;
        color: #fff;
    }

    .size-btn:hover {
        background-color: #007bff;
        color: #fff;
    }

    .quantity {
        margin-top: 20px;
    }

    #quantity {
        width: 60px;
        text-align: center;
        padding: 5px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin: 0 10px;
    }

    button#increase,
    button#decrease {
        color: black;
        padding: 10px;
        /* border: none; */
        /* border-radius: 100%; */
        /* cursor: pointer; */
        transition: background-color 0.3s ease;
    }

    .btn-secondary {
        margin-top: 30px;
        padding: 12px 20px;
        background-color: #6c757d;
        color: white;
        border-radius: 8px;
        font-size: 16px;
        text-align: center;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <h1>Chi tiết sản phẩm</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="product-detail-container">
                <div class="product-images">
                    <img src="{{ asset($product->images[0]) }}" alt="Product Image" class="main-image" id="main-image">
                    <div class="thumbs">
                        @php
                        $images = json_decode($product->image, true);
                        @endphp
                        @if ($images)
                        @foreach ($images as $image)
                        <img src="{{ asset($image) }}" alt="Product Thumbnail" class="thumb" onclick="changeImage(this)">
                        @endforeach
                        @else
                        <p>Không có hình ảnh nào.</p>
                        @endif
                    </div>
                </div>
                <div class="product-info">
                    <h3>Thông tin sản phẩm</h3>
                    <div class="product-details">
                        <p><strong>Tên sản phẩm:</strong> {{ $product->name }}</p>
                        <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                        <p><strong>Giá khuyến mãi:</strong> {{ $product->price_sale ? number_format($product->price_sale, 0, ',', '.') . ' VNĐ' : 'Không có' }}</p>
                        <p><strong>Mô tả:</strong> {{ $product->description }}</p>
                        <p><strong>Danh mục:</strong> {{ $product->category->name }}</p>
                        <p><strong>Thương hiệu:</strong> {{ $product->brand->name }}</p>
                        <p><strong>Tag:</strong> {{ $product->tag->name }}</p>
                    </div>

                    <div class="size-options">
                        <strong>Size:</strong>
                        @foreach($product->sizes as $size)
                        <button
                            class="size-btn"
                            data-size="{{ $size->id }}"
                            data-quantity="{{ $size->quantity }}"
                            onclick="selectSize(this)">
                            {{ $size->sizenumber }} ({{ $size->quantity }} có sẵn)
                        </button>
                        @endforeach
                    </div>

                    <div class="quantity">
                        <strong>Số lượng:</strong>
                        <button id="decrease">-</button>
                        <input type="number" id="quantity" value="1" min="1">
                        <button id="increase">+</button>
                    </div>

                    <div class="actions">
                        <!-- Shared hidden inputs for size_id and quantity -->
                        <!-- <input type="hidden" name="size_id" id="size_id">
                        <input type="hidden" name="quantity" id="quantity_input" value="1"> -->
                        <!-- <input type="hidden" name="items" id="items-input"> -->

                        <!-- Form for adding to cart -->
                        <form action="{{ route('cart.add') }}" method="POST" id="cart-form">
                            @csrf
                            <input type="hidden" name="size_id" id="size_id">
                            <input type="hidden" name="quantity" id="quantity_input" value="1">
                            <!-- <input type="hidden" name="items" id="items-input"> -->
                            <button type="submit" class="add-cart-btn">Thêm vào giỏ hàng</button>
                        </form>

                        <!-- Form for placing an order -->
                        <form action="{{ route('order.buynow') }}" method="POST" id="order-form">
                            @csrf
                            <!-- <input type="hidden" name="size_id" id="size_id">
                            <input type="hidden" name="quantity" id="quantity_input" value="1"> -->
                            <input type="hidden" name="items" id="items-input">
                            <button type="submit" class="buy-btn">Mua ngay</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Error Display -->
            @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="row mt-3">
                <div class="col-md-12">
                    <a href="{{ route('shop') }}" class="btn btn-secondary">Quay lại danh sách sản phẩm</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    let selectedSize = null;

    function updateSharedInputs(sizeId, quantity) {
        document.getElementById('size_id').value = sizeId;
        document.getElementById('quantity_input').value = quantity;
    }

    function selectSize(button) {
        document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        const sizeId = button.getAttribute('data-size');
        document.getElementById('size_id').value = sizeId;
        const maxQuantity = button.getAttribute('data-quantity');
        document.getElementById('quantity').setAttribute('max', maxQuantity);
        document.getElementById('quantity').value = 1; // Reset quantity to 1 when a new size is selected
    }


    document.getElementById('increase').addEventListener('click', function() {
        let quantityInput = document.getElementById('quantity');
        let maxQuantity = quantityInput.getAttribute('max');
        if (parseInt(quantityInput.value) < parseInt(maxQuantity)) {
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }
    });

    document.getElementById('decrease').addEventListener('click', function() {
        let quantityInput = document.getElementById('quantity');
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    });

    document.querySelector('.buy-btn').addEventListener('click', function(event) {
        const selectedSize = document.querySelector('.size-btn.active');
        if (!selectedSize) {
            alert('Vui lòng chọn size trước khi mua ngay!');
            event.preventDefault();
            return;
        }

        const sizeId = selectedSize.getAttribute('data-size');
        const availableQuantity = parseInt(selectedSize.getAttribute('data-quantity'), 10);
        const quantityInput = document.getElementById('quantity');
        const quantity = parseInt(quantityInput.value, 10);

        if (isNaN(quantity) || quantity <= 0) {
            alert('Số lượng không hợp lệ. Vui lòng nhập số lượng lớn hơn 0.');
            event.preventDefault();
            return;
        }

        if (quantity > availableQuantity) {
            alert('Số lượng yêu cầu vượt quá số lượng có sẵn trong kho.');
            event.preventDefault();
            return;
        }

        // Update the hidden input with structured items data
        const itemsData = [{
            size_id: sizeId,
            quantity: quantity
        }];
        document.getElementById('items-input').value = JSON.stringify(itemsData);

        console.log('Mua Ngay: Items =', itemsData);
    });


    document.querySelector('.add-cart-btn').addEventListener('click', function(event) {
        // Find the active size button
        const selectedSize = document.querySelector('.size-btn.active');
        if (!selectedSize) {
            alert('Vui lòng chọn size trước khi thêm vào giỏ hàng!');
            event.preventDefault(); // Prevent the form from being submitted if size is not selected
            return;
        }

        // Get the size ID and available quantity
        const sizeId = selectedSize.getAttribute('data-size');
        const availableQuantity = parseInt(selectedSize.getAttribute('data-quantity'), 10);

        // Get the quantity entered by the user
        const quantityInput = document.getElementById('quantity');
        const quantity = parseInt(quantityInput.value, 10);

        // Check if the entered quantity is valid
        if (isNaN(quantity) || quantity <= 0) {
            alert('Số lượng không hợp lệ. Vui lòng nhập số lượng lớn hơn 0.');
            event.preventDefault(); // Prevent the form from being submitted if the quantity is invalid
            return;
        }

        // Check if the requested quantity exceeds available stock
        if (quantity > availableQuantity) {
            alert('Số lượng yêu cầu vượt quá số lượng có sẵn trong kho.');
            event.preventDefault(); // Prevent the form from being submitted if the quantity exceeds stock
            return;
        }

        // Update the hidden input fields with the selected size ID and quantity
        document.getElementById('size_id').value = sizeId;
        document.getElementById('quantity_input').value = quantity;
    });


    // function buyProduct() {
    //     const sizeId = document.getElementById('size_id').value;
    //     const quantity = document.getElementById('quantity').value;
    //     if (!sizeId) {
    //         alert('Vui lòng chọn size trước khi mua!');
    //         return;
    //     }
    //     alert(`Mua ngay: Size ${sizeId}, Số lượng: ${quantity}`);
    // }


    // Thay đổi hình ảnh chính khi nhấn vào hình nhỏ
    function changeImage(thumbnail) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = thumbnail.src;
    }
</script>
@endsection