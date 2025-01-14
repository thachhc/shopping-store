@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
@section('content')



<div class="container">
    <h1>Your Shopping Cart</h1>

    @if (empty($cartItems))
    <p>Your cart is currently empty.</p>
    @else
    <form action="{{ route('order.index') }}" method="POST" id="order-form">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                <tr>
                    <td>
                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" data-size="{{ $item->size_id }}">
                    </td>
                    <td>
                        @php
                        $originalString = $item->product_image;
                        $decodedString = json_decode($originalString, true);
                        if (is_string($decodedString)) {
                        $decodedString = json_decode($decodedString, true);
                        }
                        $images = is_array($decodedString) ? $decodedString : [];
                        $count = count($images);
                        @endphp

                        @if ($count > 0)
                        <img src="{{ asset($images[0]) }}" width="80px" alt="Product Thumbnail" class="thumb">
                        @else
                        <p>No images available.</p>
                        @endif
                    </td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->size_number }}</td>
                    <td>
                        <form action="{{ route('cart.decrease', $item->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">-</button>
                        </form>
                        {{ $item->product_quantity }}
                        <form action="{{ route('cart.increase', $item->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">+</button>
                        </form>
                    </td>
                    <td>
                        @if ($item->product_price_sale)
                        <span>{{ number_format($item->product_price_sale, 0, ',', '.') }} VND</span>
                        @else
                        {{ number_format($item->product_price, 0, ',', '.') }} VND
                        @endif
                    </td>
                    <td>
                        @if ($item->product_price_sale)
                        {{ number_format($item->product_price_sale * $item->product_quantity, 0, ',', '.') }} VND
                        @else
                        {{ number_format($item->product_price * $item->product_quantity, 0, ',', '.') }} VND
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h4 style="font-size: 24px; font-weight: bold; text-align: right;">Total Price: {{ number_format($totalPrice, 0, ',', '.') }} VND</h4>

        <a href="{{ route('dashboard') }}" class="btn btn-primary">Continue Shopping</a>

        <!-- Order Now Button -->
        <button type="submit" class="btn btn-success buy-btn">Buy Now</button>
    </form>
    @endif
</div>

<!-- JavaScript Code -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        debugger;
        // Select all cart-item checkboxes
        const checkboxes = document.querySelectorAll(".cart-item-checkbox");

        checkboxes.forEach(function(checkbox) {
            // Ensure checkbox is not null
            if (checkbox) {
                // Add an event listener or manipulate properties safely
                checkbox.addEventListener("change", function() {
                    console.log("Checkbox value:", checkbox.value);
                });
            } else {
                console.warn("Checkbox not found in the DOM.");
            }
        });
    });

    document.querySelector('.buy-btn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent form submission until validation passes
        const selectedItems = document.querySelectorAll('input[name="selected_items[]"]:checked');

        if (selectedItems.length === 0) {
            alert('Please select products to purchase!');
            console.log('No items were selected for purchase.');
            return;
        }

        const itemsData = [];
        try {
            for (const item of selectedItems) {
                const sizeId = item.getAttribute('data-size');
                console.log(`Processing size ID: ${sizeId}`);

                if (!sizeId) {
                    alert('Size information not found.');
                    console.error('Size ID is missing for item.');
                    return;
                }

                const productQuantityElement = item.closest('tr').querySelector('td:nth-child(5)');
                if (!productQuantityElement) {
                    console.error(`Product quantity element not found for size ID ${sizeId}`);
                    alert('Product quantity information not found.');
                    return;
                }

                const quantityText = productQuantityElement.innerText.trim();
                console.log(`Raw quantity text for size ID ${sizeId}:`, quantityText);

                const quantityNumber = parseInt(quantityText.replace(/[^0-9]/g, ''), 10);
                if (isNaN(quantityNumber) || quantityNumber <= 0) {
                    alert('Invalid quantity. Please check the quantity again.');
                    console.error(`Invalid quantity for size ID ${sizeId}:`, quantityText);
                    return;
                }

                const availableQuantity = parseInt(item.closest('tr').getAttribute('data-available-quantity'), 10);
                if (quantityNumber > availableQuantity) {
                    alert('Requested quantity exceeds available stock.');
                    console.error(`Requested quantity for size ID ${sizeId} exceeds available quantity.`);
                    return;
                }

                itemsData.push({
                    size_id: sizeId,
                    quantity: quantityNumber
                });
            }

            if (itemsData.length > 0) {
                console.log('Items data before form submission:', itemsData);

                let itemsInput = document.getElementById('items-input');
                if (!itemsInput) {
                    itemsInput = document.createElement('input');
                    itemsInput.type = 'hidden';
                    itemsInput.id = 'items-input';
                    itemsInput.name = 'items_data';
                    document.getElementById('order-form').appendChild(itemsInput);
                }

                itemsInput.value = JSON.stringify(itemsData);
                console.log('Updated hidden input value:', itemsInput.value);

                document.getElementById('order-form').submit();
            }
        } catch (error) {
            console.error('An error occurred while processing the items data:', error);
            alert('An error occurred while processing your order. Please try again.');
        }
    });

    function selectSize(button) {
        document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        const sizeId = button.getAttribute('data-size');
        document.getElementById('size_id').value = sizeId;
        const maxQuantity = button.getAttribute('data-quantity');
        document.getElementById('quantity').setAttribute('max', maxQuantity);
        document.getElementById('quantity').value = 1; // Reset quantity to 1 when a new size is selected
    }
</script>
@endsection
