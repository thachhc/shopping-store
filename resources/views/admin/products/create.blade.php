<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product update</title>
    <link rel="stylesheet" href="/css/admin/createProduct.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<body>
    
    <div class="container mt-5">
        <h1 class="text-center">New Product</h1>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" name="name" class="form-control" required>
                <label for="name" class="floating-label">Name</label>
            </div>

            <div class="form-group">
                <select name="brand_id" class="form-control" required>
                    @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                <label for="brand_id" class="floating-label">Brand</label>
            </div>

            <div class="form-group">
                <select name="category_id" class="form-control" required>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <label for="category_id" class="floating-label">Category</label>
            </div>

            <div class="form-group">
                <select name="tag_id" class="form-control" required>
                    @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
                <label for="tag_id" class="floating-label">Product Tag</label>
            </div>

            <div class="form-group">
                <input type="number" name="price" id="price" class="form-control" required>
                <label for="price" class="floating-label">Price</label>
            </div>

            <div class="form-group">
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
                <label for="discount" class="floating-label">Discount (%)</label>
            </div>

            <div class="form-group">
                <input type="text" name="price_sale" id="price_sale" class="form-control" readonly>
                <label for="price_sale" class="floating-label">Price Sale</label>
            </div>

            <div class="form-group">
                <textarea name="description" class="form-control"></textarea>
                <label for="description" class="floating-label">Description</label>
            </div>
            <div class="form-group">
                <select name="status" class="form-control" required>
                    <option value="1">In Stock</option>
                    <option value="0">Out of Stock</option>
                </select>
                <label for="status" class="floating-label">Status</label>
            </div>

            <h2>Size & Quantity</h2>
            <div id="size-quantity-container">
                <div class="form-group row align-items-center mb-2" id="size-1">
                    <div class="col-md-6">
                        <input type="text" name="size[]" class="form-control" placeholder="Size 1">
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="quantity[]" class="form-control" placeholder="Quantity"
                            min="1">
                    </div>
                    <div class="col-md-1 text-center">
                        <button type="button" class="btn btn-success" onclick="addSizeInput()">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="size-quantity-container"></div>

            <div class="image-preview" id="image-preview"></div>

            <div class="form-group" style="display:inline-block; position:relative; margin: 10px;">
                <input type="file" name="images[]" class="form-control" multiple required accept="image/*"
                    style="display: none;" onchange="previewImages(event)">
                <div onclick="document.querySelector('input[name=\'images[]\']').click();"
                    style="cursor: pointer; border: 1px dashed #ccc; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 24px; color: #ccc;">&#43;</span>
                </div>
            </div>

            <br />
            <button type="submit" class="btn btn-success">Save Product</button>       
            <button type="submit" class="btn btn-secondary">Cancel</button>
        </form>
    </div>

</html>
<script>
    let sizeCount = 1; // Track number of sizes added

    // Add a new size input
    function addSizeInput() {
        sizeCount++;
        const container = document.getElementById('size-quantity-container');
        // Create new size input group
        const groupDiv = document.createElement('div');
        groupDiv.classList.add('form-group', 'row', 'align-items-center', 'mb-2');
        groupDiv.id = `size-${sizeCount}`;

        const sizeInputContainer = document.createElement('div');
        sizeInputContainer.classList.add('col-md-6');
        const sizeInput = document.createElement('input');
        sizeInput.type = 'text';
        sizeInput.name = `size[]`;
        sizeInput.classList.add('form-control');
        sizeInput.placeholder = `Size ${sizeCount}`;
        sizeInputContainer.appendChild(sizeInput);

        const quantityInputContainer = document.createElement('div');
        quantityInputContainer.classList.add('col-md-5');
        const quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.name = `quantity[]`;
        quantityInput.classList.add('form-control');
        quantityInput.placeholder = `Quantity`;
        quantityInput.min = "1";
        quantityInputContainer.appendChild(quantityInput);

        const removeButtonContainer = document.createElement('div');
        removeButtonContainer.classList.add('col-md-1', 'text-center');
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger');
        removeButton.innerHTML = '<i class="bi bi-x-circle"></i>';
        removeButton.onclick = function() {
            groupDiv.remove();
        };
        removeButtonContainer.appendChild(removeButton);

        groupDiv.appendChild(sizeInputContainer);
        groupDiv.appendChild(quantityInputContainer);
        groupDiv.appendChild(removeButtonContainer);
        container.appendChild(groupDiv);
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