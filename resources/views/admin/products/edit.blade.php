<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List product') }}
        </h2>
    </x-slot>
    <div class="container">
        <h1>Product update: {{ $product->name }}</h1>
        <form id="product-form" action="{{ route('products.update', $product->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}"
                    required>
                <label for="name" class="floating-label">Product name</label>
            </div>

            <div class="form-group">
                <label for="brand_id">Brand</label>
                <select name="brand_id" class="form-control" required>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                            {{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tag_id">Product tag</label>
                <select name="tag_id" class="form-control" required>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" {{ $tag->id == $product->tag_id ? 'selected' : '' }}>
                            {{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="discount">Discount (%)</label>
                <select name="discount" class="form-control" onchange="calculateSalePrice()">
                    @for ($i = 0; $i <= 30; $i += 5)
                        <option value="{{ $i }}" {{ $product->discount == $i ? 'selected' : '' }}>
                            {{ $i }}%</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="price_sale">Price sale</label>
                <input type="text" name="price_sale" class="form-control" id="price_sale"
                    value="{{ old('price_sale', $product->price_sale) }}" readonly>
            </div>


            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <!-- Hình ảnh hiện tại -->
                <h5>Current image:</h5>
                <div class="image-preview" id="image-preview">
                    @if ($product->image)
                        @foreach (json_decode($product->image) as $key => $image)
                            <div id="image-{{ $key }}"
                                style="display:inline-block; position:relative; margin: 10px;">
                                <img src="{{ asset($image) }}" width="100" height="100" alt="Hình ảnh sản phẩm"
                                    onclick="selectImage('{{ $key }}')">
                                <span class="remove-icon" onclick="removeImage('{{ $key }}')"
                                    style="cursor: pointer; color: white; font-size: 16px; background-color: red; border-radius: 50%; padding: 2px 5px; position: absolute; top: 0; right: 0;">&times;</span>
                                <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                <input type="file" name="updated_images[{{ $key }}]" accept="image/*"
                                    style="display:none;" onchange="previewUpdatedImage(event, '{{ $key }}')">
                            </div>
                        @endforeach
                    @endif
                </div>


                <!-- Hình ảnh mới -->
                <h5>New image:</h5>
                <div id="new-images-preview" class="image-preview" style="margin-top: 10px;"></div>
                <div id="add-image" style="display:inline-block; position:relative; margin: 10px;">
                    <input type="file" name="new_images[]" class="form-control" multiple accept="image/*"
                        style="display: none;" onchange="previewImages(event)">
                    <div onclick="document.querySelector('input[name=\'new_images[]\']').click();"
                        style="cursor: pointer; border: 1px dashed #ccc; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 24px; color: #ccc;">&#43;</span> <!-- Biểu tượng dấu cộng -->
                    </div>
                </div>
            </div>

            <input type="hidden" id="removed-images" name="removed_images" value="">
            <input type="hidden" id="product-id" data-id="{{ $product->id }}">
            <input type="hidden" name="redirect_to_codesize" id="redirect_to_codesize" value="0">

        </form>
        <form class="btn" action="{{ route('products.index') }}" method="GET" style="display:inline;">
            <button type="button" class="btn btn-success" onclick="confirmUpdate()">Product update</button>
            <button type="submit" class="btn btn-secondary">Return product list</button>
        </form>
    </div>

    <script>
        let removedImages = []; // Mảng lưu các hình ảnh đã xóa
        const productId = document.getElementById('product-id').getAttribute('data-id'); // Lấy ID sản phẩm từ thẻ

        function confirmUpdate() {
            const result = confirm("Bạn có muốn chỉnh sửa codesize không?");
            document.getElementById('redirect_to_codesize').value = result ? "1" : "0";
            document.getElementById('product-form').submit();
        }

        function removeImage(key) {
            // Xóa ảnh khỏi giao diện người dùng
            document.getElementById(`image-${key}`).remove();

            // Thêm ảnh đã xóa vào mảng removedImages để gửi tới server
            removedImages.push(key);

            // Tạo input hidden lưu removedImages
            document.getElementById('removed-images').value = JSON.stringify(removedImages);
        }

        function selectImage(key) {
            // Tìm input file tương ứng và mở hộp thoại chọn file
            const input = document.querySelector(`input[name="updated_images[${key}]"]`);
            if (input) {
                input.click(); // Mở hộp thoại chọn file
            }
        }

        function calculateSalePrice() {
            const priceField = document.querySelector('input[name="price"]');
            const discountField = document.querySelector('select[name="discount"]');
            const priceSaleField = document.getElementById('price_sale');

            const price = parseFloat(priceField.value);
            const discount = parseFloat(discountField.value);

            if (price && discount) {
                const priceSale = price * (1 - discount / 100);
                priceSaleField.value = priceSale.toFixed(0);
            } else {
                priceSaleField.value = '';
            }
        }
        document.querySelector('input[name="price"]').addEventListener('input', calculateSalePrice);

        function previewImages(event) {
            const previewContainer = document.getElementById('new-images-preview');
            const files = event.target.files;

            // Xóa tất cả hình ảnh đã được chọn trước đó
            previewContainer.innerHTML = '';

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Tạo một thẻ div cho hình mới
                    const newImageDiv = document.createElement('div');
                    newImageDiv.style.display = 'inline-block';
                    newImageDiv.style.position = 'relative';
                    newImageDiv.style.margin = '10px';

                    // Tạo thẻ img cho hình ảnh mới
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.width = 100;
                    img.height = 100;
                    newImageDiv.appendChild(img);

                    // Thêm icon xóa cho hình mới
                    const removeIcon = document.createElement('span');
                    removeIcon.innerHTML = '&times;';
                    removeIcon.style.cursor = 'pointer';
                    removeIcon.style.color = 'white';
                    removeIcon.style.fontSize = '16px';
                    removeIcon.style.backgroundColor = 'red';
                    removeIcon.style.borderRadius = '50%';
                    removeIcon.style.padding = '2px 5px';
                    removeIcon.style.position = 'absolute';
                    removeIcon.style.top = '0';
                    removeIcon.style.right = '0';
                    removeIcon.onclick = function() {
                        newImageDiv.remove(); // Xóa hình mới
                    };
                    newImageDiv.appendChild(removeIcon);
                    previewContainer.appendChild(newImageDiv); // Thêm hình mới vào previewContainer
                };
                reader.readAsDataURL(file);
            }
        }
        function previewUpdatedImage(event, key) {
            const previewContainer = document.getElementById(`image-${key}`);
            const files = event.target.files;

            if (files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.querySelector('img').src = e.target.result; // Cập nhật hình ảnh
                };
                reader.readAsDataURL(files[0]);
            }
        }
    </script>
</x-app-layout>
