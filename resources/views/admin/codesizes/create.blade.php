<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List product') }}
        </h2>
    </x-slot>
    <div class="container mt-5">
        <h1 class="text-center">Add Size & Quantity</h1>
        <form action="{{ route('codesizes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ request()->get('product_id') }}">

            <div class="form-group">
                <label for="total_sizes">Tổng Số Lượng Size</label>
                <input type="number" id="total_sizes" class="form-control" min="1"
                    onchange="generateSizeInputs()" required>
            </div>

            <div id="size-quantity-container"></div>

            <button type="submit" class="btn btn-primary">Save Sizes</button>
        </form>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Return to Dashboard</a>
    </div>

    <script>
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
    </script>


</x-app-layout>
