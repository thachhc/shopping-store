<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shoe Shop - Product Listing Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">

</head>

<body>
    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <i class="fa fa-envelope mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="mailto:info@company.com">thach.hc2410@gmail.com</a>
                    <i class="fa fa-phone mx-2"></i>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="tel:010-020-0340">010-020-0340</a>
                </div>
                <div>
                    <a class="text-light" href="https://fb.com/templatemo" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://twitter.com/" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
                    <a class="text-light" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin fa-sm fa-fw"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->


    <!-- Nav -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h1 align-self-center" href="{{url('/')}}">
                Shoe
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/')}}">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/')}}">About</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/demo')}}">Shop</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{url(path: '/products')}}">Product</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/')}}">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/')}}">Order</a>
                        </li>

                    </ul>
                </div>

                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Search -->
                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#templatemo_search">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                    <!-- Cart -->
                    <a class="nav-icon position-relative text-decoration-none" href="{{url('/cart')}}">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">7</span>
                    </a>

                    <!-- login -->

                    <a class="nav-icon position-relative text-decoration-none" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                    </a>

                    <!-- Dropdown -->
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        @auth
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            {{ __('Profile') }}
                        </a>
                        <a class="dropdown-item" href="{{ Auth::user()->role == 'admin' ? url('/admin/dashboard') : url('/dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                        @else
                        <a class="dropdown-item" href="{{ url('/login') }}" :active="request()->routeIs('login')">
                            {{ __('Login') }}
                        </a>

                        @if (Route::has('register'))
                        <a class="dropdown-item" href="{{ url('/register') }}" :active="request()->routeIs('register')">
                            {{ __('Register') }}
                        </a>
                        @endif
                        @endauth
                    </div>

                </div>
            </div>

        </div>
    </nav>
    <!-- Close Nav -->

    <!-- Modal -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="get" class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                    <button type="submit" class="input-group-text bg-success text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="h2 pb-4">Categories</h1>
                <ul class="list-unstyled templatemo-accordion">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('shop') }}" id="filter-form">

                        <!-- Category Filter -->
                        <div class="filter-group mb-4">
                            <label class="form-label fw-bold" for="sport">Sport</label>
                            <div class="dropdown">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle w-100" onclick="toggleDropdown('sport-dropdown')">
                                    Select Sport
                                </button>
                                <div id="sport-dropdown" class="dropdown-menu dropdown-content">
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


                        <!-- Brand Dropdown -->
                        <div class="filter-group">
                            <label class="form-label fw-bold" for="brand">Brand</label>
                            <div class="dropdown">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle w-100" onclick="toggleDropdown('brand-dropdown')">
                                    Select Brand
                                </button>
                                <div id="brand-dropdown" class="dropdown-menu dropdown-content">
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

                        <!-- Sort By Filter -->
                        <div class="filter-group mb-4">
                            <label class="form-label fw-bold" for="sort-by">Sort By</label>
                            <div class="dropdown">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle w-100" onclick="toggleDropdown('sort-by-dropdown')">
                                    Sort By
                                </button>
                                <div id="sort-by-dropdown" class="dropdown-menu dropdown-content">
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
                                        <label for="sort-by-sale">Offers</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </ul>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-inline shop-top-menu pb-3 pt-1">
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none mr-3" href="#">All</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none mr-3" href="#">Men's</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none" href="#">Women's</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6 pb-4">
                        <div class="d-flex">
                            <form method="GET" action="{{ route('shop') }}" id="filter-form" class="form-control border-0">
                                <select class="form-select" id="sort-select" name="sort_by" onchange="this.form.submit()">
                                    <option value="">Sort By</option>
                                    <option value="name-asc" @if (request()->input('sort_by') == 'name-asc')checked
                                        @endif>Name (A to Z)</option>
                                    <option value="name-desc" @if (request()->input('sort_by') == 'name-desc')checked
                                        @endif>Name (Z to A)</option>
                                    <option value="price-asc" {{ request()->input('sort_by') == 'price-asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                    <option value="price-desc" {{ request()->input('sort_by') == 'price-desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                </select>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- products -->
                <div class="row">
                    @if($products->isEmpty())
                    <p class="text-center">No products found your search</p>
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


                    <div class="col-md-4">


                        <div class="card mb-4 product-wap rounded-0">
                            <div class="card rounded-0">
                                <img class="card-img rounded-0 img-fluid" src="{{ asset($image_url) }}" alt="{{ $product->name }}">
                                <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                    <ul class="list-unstyled">
                                        <li><a class="btn btn-success text-white" href="shop-single.html"><i class="far fa-heart"></i></a></li>
                                        <li><a class="btn btn-success text-white mt-2" href="shop-single.html"><i class="far fa-eye"></i></a></li>
                                        <li><a class="btn btn-success text-white mt-2" href="shop-single.html"><i class="fas fa-cart-plus"></i></a></li>
                                    </ul>
                                </div>

                            </div>
                            <div class="card-body text-truncate">
                                <a href="{{ url('products/' . $product->id) }}" class="h3 text-decoration-none text-truncate fw-bold">{{ $product->name }}</a>
                                <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">

                                    <li class="pt-2">
                                        <span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                        <span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                        <span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                        <span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                        <span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                    </li>
                                </ul>

                                @if($price)
                                <div class="text-center mb-0">
                                    @if($sale_price)
                                    <span class="text-muted strikethrough" style="font-size: 0.9rem;">{{ number_format((float)$price, 0, ',', '.') }} VND</span>
                                    <span class="text-danger" style="font-size: 1rem; font-weight: bold;">{{ number_format((float)$sale_price, 0, ',', '.') }} VND</span>
                                    @else
                                    <span>{{ number_format((float)$price, 0, ',', '.') }} VND</span>
                                    @endif
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    @endif
                    @endforeach
                    @endif

                </div>

                <div div="row">
                    <ul class="pagination pagination-lg justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link active rounded-0 mr-3 shadow-sm border-top-0 border-left-0" href="#" tabindex="-1">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-0 mr-3 shadow-sm border-top-0 border-left-0 text-dark" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-0 shadow-sm border-top-0 border-left-0 text-dark" href="#">3</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <!-- End Content -->

    <!-- Start Brands -->
    <section class="bg-light py-5">
        <div class="container my-4">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Our Brands</h1>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
                <div class="col-lg-9 m-auto tempaltemo-carousel">
                    <div class="row d-flex flex-row">
                        <!--Controls-->
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#multi-item-example" role="button" data-bs-slide="prev">
                                <i class="text-light fas fa-chevron-left"></i>
                            </a>
                        </div>
                        <!--End Controls-->

                        <!--Carousel Wrapper-->
                        <div class="col">
                            <div class="carousel slide carousel-multi-item pt-2 pt-md-0" id="multi-item-example" data-bs-ride="carousel">
                                <!--Slides-->
                                <div class="carousel-inner product-links-wap" role="listbox">

                                    <!--First slide-->
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End First slide-->

                                    <!--Second slide-->
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Second slide-->

                                    <!--Third slide-->
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Third slide-->

                                </div>
                                <!--End Slides-->
                            </div>
                        </div>
                        <!--End Carousel Wrapper-->

                        <!--Controls-->
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#multi-item-example" role="button" data-bs-slide="next">
                                <i class="text-light fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <!--End Controls-->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Brands-->


    <!-- Start Footer -->
    <footer class="bg-dark" id="tempaltemo_footer">
        <div class="container">
            <div class="row">

                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-success border-bottom pb-3 border-light logo">Shoe Shop</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li>
                            <i class="fas fa-map-marker-alt fa-fw"></i>
                            123 Consectetur at ligula 10660
                        </li>
                        <li>
                            <i class="fa fa-phone fa-fw"></i>
                            <a class="text-decoration-none" href="tel:010-020-0340">010-020-0340</a>
                        </li>
                        <li>
                            <i class="fa fa-envelope fa-fw"></i>
                            <a class="text-decoration-none" href="mailto:info@company.com">info@company.com</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Products</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li><a class="text-decoration-none" href="#">Luxury</a></li>
                        <li><a class="text-decoration-none" href="#">Sport Wear</a></li>
                        <li><a class="text-decoration-none" href="#">Men's Shoes</a></li>
                        <li><a class="text-decoration-none" href="#">Women's Shoes</a></li>
                        <li><a class="text-decoration-none" href="#">Popular Dress</a></li>
                        <li><a class="text-decoration-none" href="#">Gym Accessories</a></li>
                        <li><a class="text-decoration-none" href="#">Sport Shoes</a></li>
                    </ul>
                </div>

                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Further Info</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li><a class="text-decoration-none" href="#">Home</a></li>
                        <li><a class="text-decoration-none" href="#">About Us</a></li>
                        <li><a class="text-decoration-none" href="#">Shop Locations</a></li>
                        <li><a class="text-decoration-none" href="#">FAQs</a></li>
                        <li><a class="text-decoration-none" href="#">Contact</a></li>
                    </ul>
                </div>

            </div>

            <div class="row text-light mb-4">
                <div class="col-12 mb-3">
                    <div class="w-100 my-3 border-top border-light"></div>
                </div>
                <div class="col-auto me-auto">
                    <ul class="list-inline text-left footer-icons">
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="http://facebook.com/"><i class="fab fa-facebook-f fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://www.instagram.com/"><i class="fab fa-instagram fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://twitter.com/"><i class="fab fa-twitter fa-lg fa-fw"></i></a>
                        </li>
                        <li class="list-inline-item border border-light rounded-circle text-center">
                            <a class="text-light text-decoration-none" target="_blank" href="https://www.linkedin.com/"><i class="fab fa-linkedin fa-lg fa-fw"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-auto">
                    <label class="sr-only" for="subscribeEmail">Email address</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control bg-dark border-light" id="subscribeEmail" placeholder="Email address">
                        <div class="input-group-text btn-success text-light">Subscribe</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100 bg-black py-3">
            <div class="container">
                <div class="row pt-2">
                    <div class="col-12">
                        <p class="text-left text-light">
                            Copyright &copy; 2021 Company Name
                            | Designed by <a rel="sponsored" href="https://templatemo.com" target="_blank">TemplateMo</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </footer>
    <!-- End Footer -->

    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->

    <script>
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

        // Close dropdown if clicked outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown-content');
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            // Loop through each dropdown and check if the click is outside of it
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target) && !event.target.closest('.dropdown')) {
                    dropdown.style.display = 'none';
                }
            });

            // Loop through each toggle button and check if the click is outside of it
            dropdownToggles.forEach(toggle => {
                if (!toggle.contains(event.target)) {
                    toggle.classList.remove('show');
                }
            });
        });




        // Automatically submit the form when a checkbox is checked/unchecked (for brands only)
        document.querySelectorAll('input[name="brands[]"], input[name="category_id[]"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });

        // Toggle the checkbox when clicking on the entire div (including label)
        function toggleCheckbox(id) {
            const checkbox = document.getElementById(id);
            checkbox.checked = !checkbox.checked; // Toggle checkbox state
            document.getElementById('filter-form').submit(); // Submit form immediately
        }

        function toggleRadio(id) {
            const radio = document.getElementById(id);

            // If the radio button is already checked, uncheck it
            if (radio.checked) {
                radio.checked = false;
                radio.value = "";
            } else {
                radio.checked = true;
            }


            document.getElementById('filter-form').submit();
        }

        function applyFilters() {
            document.getElementById('filter-form').submit();
        }
    </script>




</body>

</html>