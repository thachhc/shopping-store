@extends('layouts.front')

@section('meta')
<meta name="description" content=" about your webstie">
@endsection

@section('title')
<title>Shoes</title>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css\browse\browse.css') }}">
@endsection

@section('content')
<div class="container">
    <h1 class="header-title">
        {{ isset($brand) ? "Products from $brand->name" : (isset($category) ? "$category->name Zone" : "All Products") }}
    </h1>

    <!-- Filter Button -->
    <div class="button-container">
        <button class="btn filter-button" onclick="toggleFilterPanel()">Filter</button>
    </div>

    <!-- Side Panel for Filters -->
    <div id="filter-panel" class="filter-panel">
        <div class="filter-header">
            <!-- <h2>Filter Products</h2> -->
            <div class="button-container">
                <button class="close-button" onclick="toggleFilterPanel()">✕</button>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('browse') }}" id="filter-form">
            <!-- Category Filter -->
            <div class="filter-group">
                <label for="sport">Sport</label>
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle" onclick="toggleDropdown('sport-dropdown')">
                        Select Sport
                    </button>
                    <div id="sport-dropdown" class="dropdown-content">
                        @foreach($categories as $category)
                        <div class="category-option" onclick="toggleCheckbox('category-{{ $category->id }}')">
                            <input type="checkbox" id="category-{{ $category->id }}" name="category_id[]" value="{{ $category->id }}"
                                @if(in_array($category->id, request()->input('category_id', []))) checked @endif>
                            <label for="category-{{ $category->id }}" style="cursor: pointer;">
                                {{ $category->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sort By Filter -->
            <div class="filter-group">
                <label for="sort-by">Sort By</label>
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle" onclick="toggleDropdown('sort-by-dropdown')">
                        Sort By
                    </button>
                    <div id="sort-by-dropdown" class="dropdown-content">
                        <div class="sort-option" onclick="toggleRadio('sort-by-featured')">
                            <input type="radio" name="sort_by" id="sort-by-featured" value="featured"
                                @if(request()->input('sort_by') == 'featured') checked @endif>
                            <label for="sort-by-featured">Featured</label>
                        </div>
                        <div class="sort-option" onclick="toggleRadio('sort-by-newest')">
                            <input type="radio" name="sort_by" id="sort-by-newest" value="newest"
                                @if(request()->input('sort_by') == 'newest') checked @endif>
                            <label for="sort-by-newest">Newest</label>
                        </div>
                        <div class="sort-option" onclick="toggleRadio('sort-by-sale')">
                            <input type="radio" name="sort_by" id="sort-by-sale" value="sale"
                                @if(request()->input('sort_by') == 'sale') checked @endif>
                            <label for="sort-by-sale">Sale & Offers</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Size Filter -->
            <div class="filter-group">
                <label for="size">Size</label>
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle" onclick="toggleDropdown('size-dropdown')">
                        Select Size
                    </button>
                    <div id="size-dropdown" class="dropdown-content">
                        @php
                        $uniqueSizes = $sizes->pluck('sizenumber')->unique()->sort()->values();
                        @endphp

                        @foreach($uniqueSizes as $size)
                        <div class="size-option" onclick="toggleCheckbox('size-{{ $size }}')">
                            <input type="checkbox" name="sizes[]" id="size-{{ $size }}" value="{{ $size }}"
                                @if(in_array($size, request()->input('sizes', []))) checked @endif>
                            <label for="size-{{ $size }}">{{ $size }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Brand Dropdown -->
            <div class="filter-group">
                <label for="brand">Brand</label>
                <div class="dropdown">
                    <button type="button" class="dropdown-toggle" onclick="toggleDropdown('brand-dropdown')">
                        Select Brand
                    </button>
                    <div id="brand-dropdown" class="dropdown-content">
                        @foreach($brands as $otherBrand)
                        <div class="brand-option" onclick="toggleCheckbox('brand-{{ $otherBrand->id }}')">
                            <input type="checkbox" id="brand-{{ $otherBrand->id }}" name="brands[]" value="{{ $otherBrand->id }}"
                                @if(in_array($otherBrand->id, request()->input('brands', []))) checked @endif>
                            <label for="brand-{{ $otherBrand->id }}" style="cursor: pointer;">
                                {{ $otherBrand->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Main Product Grid -->
    <div id="product-grid" class="row mt-4">
        @if($products->isEmpty())
        <p class="text-center">No products found for your search query.</p>
        @else
        @foreach($products as $product)
        @php
        // Decode the JSON-encoded image attribute and get the first image
        $images = json_decode($product->image, true);
        $image_url = $images[0] ?? ''; // Use the first image if available
        $price = $product->price ?? ''; // Get the product price if it exists
        $sale_price = $product->price_sale ?? ''; // Get the sale price if it exists
        $tag = $product->tag->name ?? ''; // Assuming there's a tag field in the product model
        @endphp
        @if($image_url)
        <div class="mb-4 product-item">
            <a href="{{ url('products/' . $product->id) }}" class="card h-100 text-decoration-none">
                <div class="card product-card">
                    <div class="image-container">
                        <img src="{{ asset($image_url) }}" alt="{{ $product->name }}" class="product-image img-fluid">
                    </div>
                    <div class="card-body">
                        <!-- Display the tag -->
                        @if(!empty($tag) && $tag !== 'None')
                        <div class="product-tag">
                            <span>{{ $tag }}</span>
                        </div>
                        @endif

                        <!-- Product name -->
                        <h5 class="product-name">{{ $product->name }}</h5>

                        <!-- Display pricing -->
                        @if($price)
                        <!-- Display the original price with a strikethrough class if there is a sale price -->
                        <p class="original-price {{ $sale_price ? 'strikethrough' : '' }}">
                            {{ number_format($price, 0, ',', '.') }} VND
                        </p>
                        @endif

                        @if($sale_price)
                        <!-- Display the sale price if available -->
                        <p class="sale-price">
                            {{ number_format($sale_price, 0, ',', '.') }} VND
                        </p>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        @endif
        @endforeach
        @endif
    </div>



</div>
@endsection

@section('script')
<script>
    // Toggle filter panel visibility and adjust product grid
    function toggleFilterPanel() {
        const filterPanel = document.getElementById("filter-panel");
        const productGrid = document.getElementById("product-grid");

        // Toggle the 'active' class for filter panel visibility
        filterPanel.classList.toggle("active");

        // Save the state in localStorage
        if (filterPanel.classList.contains("active")) {
            localStorage.setItem("filterPanelState", "active");
        } else {
            localStorage.setItem("filterPanelState", "inactive");
        }

        // Adjust the product grid layout
        adjustProductGrid();
    }

    // Adjust the product grid layout when the filter panel is toggled
    function adjustProductGrid() {
        const filterPanel = document.getElementById("filter-panel");
        const productGrid = document.getElementById("product-grid");

        // Check if filter panel is active and adjust grid positioning
        if (filterPanel.classList.contains("active")) {
            productGrid.style.marginLeft = "300px"; // Adjust this for correct positioning
            productGrid.style.transition = "margin-left 0.3s ease";
        } else {
            productGrid.style.marginLeft = "0"; // Restore original grid layout
            productGrid.style.transition = "margin-left 0.3s ease";
        }
    }

    let openDropdown = null; // Variable to track the currently open dropdown

    // Dropdown toggle function
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (openDropdown && openDropdown !== dropdown) {
            openDropdown.style.display = 'none';
        }
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        openDropdown = dropdown.style.display === 'block' ? dropdown : null;
    }

    // Restore the state of the filter panel on page load
    document.addEventListener('DOMContentLoaded', () => {
        const filterPanel = document.getElementById("filter-panel");
        const savedState = localStorage.getItem("filterPanelState");

        if (savedState === "active") {
            filterPanel.classList.add("active");
        }

        // Adjust the product grid layout based on the loaded state
        adjustProductGrid();
    });


    // Close dropdown if clicked outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('.dropdown-content');
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        // Loop through each dropdown and check if the click is outside of it
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(event.target) && !event.target.closest('.dropdown')) {
                dropdown.classList.remove('show');
            }
        });

        // Loop through each toggle button and check if the click is outside of it
        dropdownToggles.forEach(toggle => {
            if (!toggle.contains(event.target)) {
                toggle.classList.remove('show');
            }
        });
    });


    // Automatically submit the form when a checkbox is checked/unchecked
    document.querySelectorAll('input[name="brands[]"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });

    // Ensure the size filter is also set up
    document.querySelectorAll('input[name="sizes[]"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });

    // Toggle the checkbox when clicking on the entire div (including label)
    function toggleCheckbox(id) {
        document.getElementById(id).click();
        setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 500); // 500ms delay for a smooth transition
    }

    function toggleRadio(id) {
        const radio = document.getElementById(id);

        // If the radio button is already checked, uncheck it
        if (radio.checked) {
            radio.checked = false;
        } else {
            radio.checked = true;
        }

        // Submit the form after a smooth transition
        setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 500); // 500ms delay for a smooth transition
    }

    function applyFilters() {
        document.getElementById('filter-form').submit();
    }
</script>
@endsection