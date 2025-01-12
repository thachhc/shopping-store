@extends('layouts.front')

@section('meta')
<meta name="description" content=" about your webstie">
@endsection

@section('title')
<title>Homepage</title>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css\homepage\homepage.css') }}">
@endsection

@section('content')
<div class="container-fluid" style="max-width: 100%; margin: 0 auto;"> <!-- Updated to use container-fluid -->

<!-- Carousel Section -->
<div id="promoCarousel" class="carousel slide mt-4 mx-auto" data-bs-ride="carousel" data-bs-interval="3000" style="max-width: 40%; height: auto;">
    <div class="carousel-inner" style="text-align: center;">
        <div class="carousel-item active">
            <div class="carousel-content">
                <h2 class="text-center promo-title">New Styles On Sale: Up To 40% Off</h2>
                <p class="lead promo-subtitle">Shop All Our New Markdowns</p>
            </div>
        </div>
        <div class="carousel-item">
            <div class="carousel-content">
                <h2 class="text-center promo-title">Move, Shop, Customise & Celebrate With Us</h2>
                <p class="lead promo-subtitle">No matter what you feel like doing today, it’s better as a Member.</p>
            </div>
        </div>
        <div class="carousel-item">
            <div class="carousel-content">
                <h2 class="text-center promo-title">Free Standard Delivery & 30-Day Free Returns</h2>
                <p class="lead promo-subtitle">Join Now</p>
            </div>
        </div>
    </div>

    <!-- Carousel Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev" hidden>
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next" hidden>
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

        <!-- Main Video Carousel Section -->
        <!-- <div class="d-flex justify-content-center mt-5 mb-4">
        <div id="mainVideoCarousel" class="carousel slide video-panel" data-bs-ride="carousel" data-bs-interval="" style="width: 95vw;">
            <div class="carousel-inner">
                @php

                // Filter brands that have videos and shuffle them
                $videoBrands = $brands->filter(function($brand) {
                $videoDirectory = public_path("storage/Brands/{$brand->name}/Videos");
                $videoFiles = File::glob("$videoDirectory/*.mp4");
                return !empty($videoFiles);
                })->shuffle(); // Shuffle the filtered brands
                @endphp

                @foreach($videoBrands as $index => $brand)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="embed-responsive embed-responsive-16by9" style="width: 100%; height: 85vh;">
                        @php
                        // Get the first .mp4 video file from the directory
                        $videoDirectory = public_path("storage/Brands/{$brand->name}/Videos");
                        $videoFiles = File::glob("$videoDirectory/*.mp4");
                        $videoURL = $videoFiles ? asset("storage/Brands/{$brand->name}/Videos/" . basename($videoFiles[0])) : '';
                        @endphp
                        @if($videoURL)
                        <video src="{{ $videoURL }}" autoplay muted loop style="width: 100%; height: 100%; object-fit: cover;" playsinline></video>
                        @endif
                    </div>
                    <h4 class="text-center mt-2 slogan-big">Watch Our New Collection from {{ $brand->name }}!</h4>
                    <h5 class="text-center mt-1 slogan-small">Explore our latest trends today!</h5>
                </div>
                @endforeach
            </div> -->

            <!-- Carousel Controls (hidden) -->
            <!-- <a class="carousel-control-prev d-none" href="#mainVideoCarousel" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next d-none" href="#mainVideoCarousel" role="button" data-bs-slide="next">
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div> -->


    <!-- Featured Panels Section -->
    <div class="row mt-5" style="margin-bottom: 10%;">
        <div class="col-12">
            <div class="text-center mb-4" style="padding: 10px; margin-top: 2.5%;">
                <h3 class="featured-title">Featured Shoes</h3>
            </div>
        </div>
        <div class="col-12">
            <div class="carousel-wrapper">
                <button class="carousel-control-prev" type="button" id="prevBtn" aria-label="Previous">
                    <span class="fas fa-chevron-left"></span>
                </button>
                <div id="featuredCarousel" class="carousel-inner">
                    <div class="carousel-track d-flex">
                        @foreach($brands as $brand)
                            @php
                            // Get products with the "Trending" tag for each brand, sorted by total quantity in descending order, limited to 10
                            $trendingProducts = $brand->products()
                                ->whereHas('tag', function($query) {
                                    $query->where('name', 'Trending');
                                })
                                ->withSum('sizes as total_quantity', 'quantity') // Add total quantity for each product
                                ->orderBy('total_quantity', 'desc') // Sort by the calculated total quantity
                                ->take(10)
                                ->get();
                            @endphp
                            @foreach($trendingProducts as $product)
                                @php
                                $images = json_decode($product->image, true);
                                $image_url = $images[0] ?? '';
                                $price = $product->price ?? ''; // Assuming there's a price attribute
                                @endphp
                                @if($image_url)
                                     <a href="/products/{{ $product->id }}" class="product-link">                                        <div class="product-panel">
                                            <div class="image-container">
                                                <img src="{{ asset($image_url) }}" alt="{{ $product->name }}" class="shoe-image img-fluid mb-2">
                                            </div>
                                            <h5 class="text-center">{{ $product->name }}</h5>
                                            @if($price)
                                                <p class="text-center price-tag">{{ number_format($price, 0) }} VND</p> <!-- Assuming 1 USD = 23,000 VND -->
                                            @endif
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                    <button class="carousel-control-next" type="button" id="nextBtn" aria-label="Next">
                        <span class="fas fa-chevron-right"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    
    <!-- Don't Miss Section -->
    <div class="dont-miss-section">
    <!-- Single Title for Both Posters -->
    <h4 class="slogan-big">LIMITED-TIME HIGHLIGHTS</h4>

    <!-- Container for Both Posters -->
    <div class="dont-miss-container">
        <!-- First Poster Panel -->
        <div class="dont-miss-poster" id="dontMissPoster1"></div>

        <!-- Second Poster Panel -->
        <div class="dont-miss-poster" id="dontMissPoster2"></div>
        </div>
    </div>


    <!-- See What's New Section -->
    <div class="row mt-5" style="margin-bottom: 10%;">
        <div class="col-12">
            <div class="text-center mb-4" style="padding: 10px; margin-top: 2.5%;">
                <h3 class="featured-title">New Arrivals</h3>
            </div>
        </div>
        <div class="col-12">
            <div class="carousel-wrapper">
                <button class="carousel-control-prev" type="button" id="prevBtn" aria-label="Previous">
                    <span class="fas fa-chevron-left"></span>
                </button>
                <div id="featuredCarousel" class="carousel-inner">
                    <div class="carousel-track d-flex">
                        @foreach($brands as $brand)
                        @php
                        // Get products with the "New Arrivals" tag for each brand, sorted by total quantity in descending order, limited to 10
                        $newArrivals = $brand->products()
                            ->whereHas('tag', function($query) {
                                $query->where('name', 'New Arrivals');
                            })
                            ->withSum('sizes as total_quantity', 'quantity') // Add total quantity for each product
                            ->orderBy('total_quantity', 'desc') // Sort by the calculated total quantity
                            ->take(10)
                            ->get();
                        @endphp
                        @foreach($newArrivals as $product)
                        @php
                        $images = json_decode($product->image, true);
                        $image_url = $images[0] ?? '';
                        $price = $product->price ?? ''; // Assuming there's a price attribute
                        @endphp
                        @if($image_url)
                        <a href="/products/{{ $product->id }}" class="product-link">
                            <div class="product-panel">
                                <div class="image-container">
                                    <img src="{{ asset($image_url) }}" alt="{{ $product->name }}" class="shoe-image img-fluid mb-2">
                                </div>
                                <h5 class="text-center">{{ $product->name }}</h5>
                                @if($price)
                                <p class="text-center price-tag">{{ number_format($price, 0) }} VND</p> <!-- Assuming 1 USD = 23,000 VND -->
                                @endif
                            </div>
                        </a>
                        @endif
                        @endforeach
                        @endforeach
                    </div>
                    <button class="carousel-control-next" type="button" id="nextBtn" aria-label="Next">
                        <span class="fas fa-chevron-right"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Shop by Sport Section -->
    <div class="row mt-5 mb-5"> <!-- Add `mb-5` here to create bottom margin -->
        <div class="col-12">
            <div class="text-center mb-4" style="padding: 10px;">
                <h4 class="slogan-big">SHOP BY SPORT</h4>
            </div>
        </div>
        <div class="col-12">
            <div class="scrollable-semi-carousel">
                @foreach($categories as $category)
                @php
                $categoryPath = public_path("storage/ShopBySport/{$category->name}");

                // Get all image files in the category's directory
                $images = File::files($categoryPath);

                // Set the first image as the background or use a fallback
                $categoryImage = isset($images[0])
                ? asset("storage/ShopBySport/{$category->name}/" . $images[0]->getFilename())
                : 'https://path-to-high-res-fallback-image.jpg';
                @endphp

                <!-- Link the category panel to a productsByCategory route -->
                <a href="{{ route('productsByCategory', ['categoryId' => $category->id]) }}" class="category-panel-link">
                    <div class="category-panel">
                        <div class="category-image" style="background-image: url('{{ $categoryImage }}');">
                            <h6 class="category-text">{{ $category->name }}</h6>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Title for Featured Panels Section -->
    <div class="brand-title text-center mb-4" style="padding: 10px;">
        <h3 class="slogan-big">"Discover the Icons of Style!"</h3>
        <p class="featured-subtitle">Explore Top Brands That Redefine Every Step</p>
    </div>

    <!-- Featured Panels Section -->
<div class="row mt-4">
    @foreach($brands as $brand)
    @php
        $brandPath = public_path("storage/Brands/{$brand->name}/Images");

        // Get all image files in the directory
        $images = File::files($brandPath);

        // Define a fallback image
        $fallbackImage = 'https://path-to-high-res-fallback-image.jpg';

        // Use the first two images found in the directory, if available
        $firstImage = isset($images[0]) ? asset("storage/Brands/{$brand->name}/Images/" . $images[0]->getFilename()) : $fallbackImage;
        $secondImage = isset($images[1]) ? asset("storage/Brands/{$brand->name}/Images/" . $images[1]->getFilename()) : $fallbackImage;
    @endphp

    <div class="col-md-4 mb-4">
        <!-- Link to the products by brand page -->
        <a href="{{ route('brand.products', ['brandId' => $brand->id]) }}" class="text-decoration-none">
            <div class="brand-panel position-relative">
                <div class="image-container">
                    <div class="first-image" style="background-image: url('{{ $firstImage }}');"></div>
                    <div class="second-image" style="background-image: url('{{ $secondImage }}');"></div>
                </div>
                <div class="overlay">
                    <h3>{{ $brand->name }}</h3>
                    <p class="brand-slogan">"Step Up Your Game with {{ $brand->name }}!"</p>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
</div>

@endsection

@section('script')

<script>
    //
        document.addEventListener('DOMContentLoaded', function() {
        const track = document.querySelector('.carousel-track');
        const panels = Array.from(document.querySelectorAll('.product-panel'));
        const totalPanels = panels.length; // Count of product panels
        const panelWidth = 400; // Width of each panel
        let currentIndex = 0; // Start at the first panel
        let isAnimating = false; // To prevent multiple clicks during animation

        // Move the carousel
        function moveCarousel(direction) {
            if (isAnimating) return; // Prevent further clicks while animating
            isAnimating = true; // Set animating to true

            currentIndex += direction;

            // Ensure currentIndex stays within bounds
            if (currentIndex < 0) {
                currentIndex = 0;
            } else if (currentIndex >= totalPanels) {
                currentIndex = totalPanels - 1;
            }

            // Apply the transition
            track.style.transition = 'transform 0.5s ease';
            track.style.transform = 'translateX(' + (-currentIndex * panelWidth) + 'px)';

            // Listen for the transition end to reset animating state
            track.addEventListener('transitionend', () => {
                isAnimating = false; // Allow new clicks after animation is done
            }, { once: true });
        }

        // Event listeners for buttons
        document.getElementById('prevBtn').addEventListener('click', function() {
            moveCarousel(-1);
        });

        document.getElementById('nextBtn').addEventListener('click', function() {
            moveCarousel(1);
        });
    });

    //Don't Miss Carousel
    const images1 = {!! json_encode($poster1Images) !!}; // First set of images
    const images2 = {!! json_encode($poster2Images) !!}; // Second set of images
    let currentIndex1 = 0;
    let currentIndex2 = 0;

    function changeImage() {
        const poster1 = document.getElementById('dontMissPoster1');
        const poster2 = document.getElementById('dontMissPoster2');

        if (poster1 && images1.length > 0) {
            poster1.style.backgroundImage = `url('${images1[currentIndex1]}')`;
            currentIndex1 = (currentIndex1 + 1) % images1.length; // Cycle through images for poster 1
        }

        if (poster2 && images2.length > 0) {
            poster2.style.backgroundImage = `url('${images2[currentIndex2]}')`;
            currentIndex2 = (currentIndex2 + 1) % images2.length; // Cycle through images for poster 2
        }
    }

    // Initialize the first images
    changeImage();
    // Change images every 5 seconds
    setInterval(changeImage, 10000);
</script>

@endsection
