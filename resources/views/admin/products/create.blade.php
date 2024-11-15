<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="{{ asset('css/products/create.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Add New Product</h1>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="category_id">Categories</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tag_id">Tag</label>
                <select name="tag_id" class="form-control" required>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="brand_id">Brand</label>
                <select name="brand_id" class="form-control" required>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="discount">Percent Discount (%)</label>
                <select name="discount" id="discount" class="form-control" onchange="calculateSalePrice()">
                    <option value="0">0%</option>
                    <option value="5">5%</option>
                    <option value="10">10%</option>
                    <option value="15">15%</option>
                    <option value="20">20%</option>
                    <option value="25">25%</option>
                    <option value="30">30%</option>
                    <option value="35">35%</option>
                    <option value="40">40%</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price_sale">Price Sale</label>
                <input type="text" name="price_sale" id="price_sale" class="form-control" readonly>
            </div>


            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control" required>
                    <option value="1">In Stock</option>
                    <option value="0">Sold Out</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="total_sizes">Tổng Số Lượng Size</label>
                <input type="number" id="total_sizes" class="form-control" min="1" onchange="generateSizeInputs()" required>
            </div>

            <div id="size-quantity-container"></div>

            <div class="image-preview" id="image-preview"></div>

            <div class="form-group" style="display:inline-block; position:relative; margin: 10px;">
                <input type="file" name="images[]" class="form-control" multiple required accept="image/*" style="display: none;" onchange="previewImages(event)">
                <div onclick="document.querySelector('input[name=\'images[]\']').click();" style="cursor: pointer; border: 1px dashed #ccc; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 24px; color: #ccc;">&#43;</span>
                </div>
            </div>

            <br />
            <button type="submit" class="btn btn-success">Save Product</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Return to Dashboard</a>
        </form>


    </div>

    <script>
         function generateSizeInputs() {
            const totalSizes = document.getElementById('total_sizes').value;
            const container = document.getElementById('size-quantity-container');
            container.innerHTML = ''; // Clear previous fields

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
        
        function calculateSalePrice() {
            const priceField = document.getElementById('price');
            const discountField = document.getElementById('discount');
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

        document.getElementById('price').addEventListener('input', calculateSalePrice);

        function previewImages(event) {
            const previewContainer = document.getElementById('image-preview');
            previewContainer.innerHTML = ''; // Xóa hình ảnh trước đó
            const files = event.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };

                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>