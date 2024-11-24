</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
</head>

<body>
    <div class="container mt-5">
        <h1>Chi tiết sản phẩm</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="product-detail-container">
                    <!-- Bên trái: Hình ảnh sản phẩm -->
                    <div class="product-images">
                        <!-- Hình ảnh chính -->
                        <img src="{{ asset($product->images[0]) }}" alt="Product Image" class="main-image" id="main-image">

                        <!-- Các hình nhỏ -->
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

                    <!-- Bên phải: Thông tin sản phẩm -->
                    <div class="product-info">
                        <div class="col-md-12">
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
                        </div>

                        <!-- Chọn size -->
                        <!-- Chọn size -->
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

                        <!-- Chọn số lượng -->
                        <div class="quantity">
                            <strong>Số lượng:</strong>
                            <button id="decrease">-</button>
                            <input type="number" id="quantity" value="1" min="1">
                            <button id="increase">+</button>
                        </div>

                        <!-- Nút hành động -->
                        <div class="actions">
                            <button class="buy-btn" onclick="buyProduct()">Mua ngay</button>
                            <button class="add-cart-btn" onclick="addToCart()">Thêm vào giỏ hàng</button>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại danh sách sản phẩm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let selectedSize = null;

        function selectSize(button) {
            selectedSize = button.getAttribute('data-size');
            let availableQuantity = button.getAttribute('data-quantity');
            document.getElementById('quantity').setAttribute('max', availableQuantity);
            document.getElementById('quantity').value = 1;
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

        function buyProduct() {
            if (selectedSize) {
                alert('Mua sản phẩm với size ' + selectedSize + ' và số lượng ' + document.getElementById('quantity').value);
            } else {
                alert('Vui lòng chọn size trước.');
            }
        }

        function addToCart() {
            if (selectedSize) {
                alert('Đã thêm sản phẩm vào giỏ hàng với size ' + selectedSize + ' và số lượng ' + document.getElementById('quantity').value);
            } else {
                alert('Vui lòng chọn size trước.');
            }
        }

        // Thay đổi hình ảnh chính khi nhấn vào hình nhỏ
        function changeImage(thumbnail) {
            const mainImage = document.getElementById('main-image');
            mainImage.src = thumbnail.src;
        }
    </script>
</body>

</html>