<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product update</title>
    <link rel="stylesheet" href="/css/admin/editProduct.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<body>
    <div class="container">
        <h1>Update Product: {{ $product->name }}</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                <label for="name" class="floating-label">Name</label>
            </div>

            <div class="form-group">
                <select name="brand_id" class="form-control" required>
                    @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach
                </select>
                <label for="brand_id" class="floating-label">Brand</label>
            </div>

            <div class="form-group">
                <select name="category_id" class="form-control" required>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}
                    </option>
                    @endforeach
                </select>
                <label for="category_id" class="floating-label">Category</label>
            </div>


            <div class="form-group">
                <select name="tag_id" class="form-control" required>
                    @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" {{ $tag->id == $product->tag_id ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                    @endforeach
                </select>
                <label for="tag_id" class="floating-label">Product Tag</label>
            </div>

            <div class="form-group">
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}"required> 
                <label for="price" class="floating-label">Price</label>
            </div>

            <div class="form-group">
                <select name="discount" class="form-control" onchange="calculateSalePrice()">
                    @for ($i = 0; $i <= 30; $i +=5)
                        <option value="{{ $i }}" {{ $product->discount == $i ? 'selected' : '' }}>
                        {{ $i }}%</option>
                        @endfor
                </select>
                <label for="discount" class="floating-label">Discount (%)</label>
            </div>

            <div class="form-group">
                <input type="text" name="price_sale" class="form-control" id="price_sale"
                    value="{{ old('price_sale', $product->price_sale) }}" readonly>
                <label for="price_sale" class="floating-label">Price sale</label>
            </div>


            <div class="form-group">
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
                <label for="description" class="floating-label">Description</label>
            </div>
            <h2>Size & Quantity</h2>
            <div id="size-quantity-container">
                @foreach ($product->sizes as $index => $size)
                <div class="form-group row align-items-center mb-2" id="size-{{ $index + 1 }}">
                    <div class="col-md-6 text-left">
                        <input type="text" name="size[]" class="form-control" value="{{ $size->sizenumber }}"
                            placeholder="Size">
                    </div>
                    <div class="col-md-5 text-right">
                        <input type="number" name="quantity[]" class="form-control" value="{{ $size->quantity }}"
                            min="1" placeholder="Số lượng">
                    </div>
                    <div class="col-md-1 text-center">
                        <!-- Nút xóa size -->
                        <button type="button" class="btn btn-danger"
                            onclick="removeSizeInput({{ $index + 1 }})">
                            <i class="bi bi-dash-circle"></i>
                        </button>
                    </div>
                </div>
                @endforeach

                <!-- Nút thêm size mới -->
                <div class="form-group row align-items-center">
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-success" onclick="addSizeInput()">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                    </div>
                </div>
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
                            style="display:none;"
                            onchange="previewUpdatedImage(event, '{{ $key }}')">
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

            <div class="btn">
                <button type="submit" class="btn btn-success">Product update</button>
                <button type="submit" class="btn btn-secondary">Return product list</button>
            </div>
        </form>
    </div>

    <script>
        let sizeCounter = {{ count($product->sizes) }}; // Số size hiện tại

        function addSizeInput() {
            sizeCounter++; // Tăng bộ đếm size
            const container = document.getElementById('size-quantity-container');

            // Tạo phần tử mới cho size
            const groupDiv = document.createElement('div');
            groupDiv.className = 'form-group row align-items-center mb-2';
            groupDiv.id = `size-${sizeCounter}`;

            groupDiv.innerHTML = `
        <div class="col-md-6 text-left">
            <input type="text" name="size[]" class="form-control" placeholder="Size ${sizeCounter}">
        </div>
        <div class="col-md-5 text-right">
            <input type="number" name="quantity[]" class="form-control" min="1" placeholder="Số lượng Size ${sizeCounter}">
        </div>
        <div class="col-md-1 text-center">
            <button type="button" class="btn btn-danger" onclick="removeSizeInput(${sizeCounter})">
                <i class="bi bi-dash-circle"></i>
            </button>
        </div>
    `;
            container.insertBefore(groupDiv, container.lastElementChild); // Thêm trước nút "+"
        }

        function removeSizeInput(id) {
            const element = document.getElementById(`size-${id}`);
            if (element) {
                element.remove();
            }
        }

        function generateSizeInputs() {
            const totalSizes = document.getElementById('total_sizes').value;
            const container = document.getElementById('size-quantity-container');
            container.innerHTML = ''; // Xóa các trường trước đó

            for (let i = 1; i <= totalSizes; i++) {
                const groupDiv = document.createElement('div');
                groupDiv.classList.add('form-group', 'row', 'align-items-center', 'mb-2');

                const sizeInputContainer = document.createElement('div');
                sizeInputContainer.classList.add('col-md-6', 'text-left');
                const sizeInput = document.createElement('input');
                sizeInput.type = 'text';
                sizeInput.name = `size[]`;
                sizeInput.classList.add('form-control');
                sizeInput.placeholder = `Size ${i}`;
                sizeInputContainer.appendChild(sizeInput);

                const quantityInputContainer = document.createElement('div');
                quantityInputContainer.classList.add('col-md-6', 'text-right');
                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.name = `quantity[]`;
                quantityInput.classList.add('form-control');
                quantityInput.placeholder = `Số lượng Size ${i}`;
                quantityInput.min = "1";
                quantityInputContainer.appendChild(quantityInput);

                groupDiv.appendChild(sizeInputContainer);
                groupDiv.appendChild(quantityInputContainer);
                container.appendChild(groupDiv);
            }
        }

        let removedImages = []; // Mảng lưu các hình ảnh đã xóa
        const productId = document.getElementById('product-id').getAttribute('data-id'); // Lấy ID sản phẩm từ thẻ

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
</body>

</html>