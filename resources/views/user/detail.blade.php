<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shoe Shop - Product Listing Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="/assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/templatemo.css">
    <link rel="stylesheet" href="/assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">

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
            <div class="container mt-5">
                <h1>Chi tiết sản phẩm</h1>
                <div class="row">
                    <div class="col-md-12">
                        <div class="product-detail-container">
                            <div class="product-images">
                                <img src="{{ asset($product->images[0]) }}" alt="Product Image" class="main-image" id="main-image">
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
                            <div class="product-info">
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

                                <div class="quantity">
                                    <strong>Số lượng:</strong>
                                    <button id="decrease">-</button>
                                    <input type="number" id="quantity" value="1" min="1">
                                    <button id="increase">+</button>
                                </div>

                                <div class="actions">
                                    <!-- Shared hidden inputs for size_id and quantity -->
                                    <!-- <input type="hidden" name="size_id" id="size_id">
                        <input type="hidden" name="quantity" id="quantity_input" value="1"> -->
                                    <!-- <input type="hidden" name="items" id="items-input"> -->

                                    <!-- Form for adding to cart -->
                                    <form action="{{ route('cart.add') }}" method="POST" id="cart-form">
                                        @csrf
                                        <input type="hidden" name="size_id" id="size_id">
                                        <input type="hidden" name="quantity" id="quantity_input" value="1">
                                        <!-- <input type="hidden" name="items" id="items-input"> -->
                                        <button type="submit" class="add-cart-btn">Thêm vào giỏ hàng</button>
                                    </form>

                                    <!-- Form for placing an order -->
                                    <form action="{{ route('order.buynow') }}" method="POST" id="order-form">
                                        @csrf
                                        <!-- <input type="hidden" name="size_id" id="size_id">
                            <input type="hidden" name="quantity" id="quantity_input" value="1"> -->
                                        <input type="hidden" name="items" id="items-input">
                                        <button type="submit" class="buy-btn">Mua ngay</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Error Display -->
                        @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <a href="{{ route('shop') }}" class="btn btn-secondary">Quay lại danh sách sản phẩm</a>
                            </div>
                        </div>
                    </div>
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
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End First slide-->

                                    <!--Second slide-->
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Second slide-->

                                    <!--Third slide-->
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="/assets/img/brand_04.png" alt="Brand Logo"></a>
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
                    <h2 class="h2 text-success border-bottom pb-3 border-light logo">Shoes Shop</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li>
                            <i class="fas fa-map-marker-alt fa-fw"></i>
                            7 Thành Thái, Q.10, Tp.Hồ Chí Minh
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
                        <li><a class="text-decoration-none" href="#">Nike</a></li>
                        <li><a class="text-decoration-none" href="#">Sport Wear</a></li>
                        <li><a class="text-decoration-none" href="#">Sneakers Shoes</a></li>
                        <li><a class="text-decoration-none" href="#">Basketball Shoes</a></li>
                        <li><a class="text-decoration-none" href="#">Fashion Shoes </a></li>
                        <li><a class="text-decoration-none" href="#">Boots Shoes </a></li>
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
    <script src="/assets/js/jquery-1.11.0.min.js"></script>
    <script src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/templatemo.js"></script>
    <script src="/assets/js/custom.js"></script>
    <!-- End Script -->

    <script>
        let selectedSize = null;

        function updateSharedInputs(sizeId, quantity) {
            document.getElementById('size_id').value = sizeId;
            document.getElementById('quantity_input').value = quantity;
        }

        function selectSize(button) {
            document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            const sizeId = button.getAttribute('data-size');
            document.getElementById('size_id').value = sizeId;
            const maxQuantity = button.getAttribute('data-quantity');
            document.getElementById('quantity').setAttribute('max', maxQuantity);
            document.getElementById('quantity').value = 1; // Reset quantity to 1 when a new size is selected
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

        document.querySelector('.buy-btn').addEventListener('click', function(event) {
            const selectedSize = document.querySelector('.size-btn.active');
            if (!selectedSize) {
                alert('Vui lòng chọn size trước khi mua ngay!');
                event.preventDefault();
                return;
            }

            const sizeId = selectedSize.getAttribute('data-size');
            const availableQuantity = parseInt(selectedSize.getAttribute('data-quantity'), 10);
            const quantityInput = document.getElementById('quantity');
            const quantity = parseInt(quantityInput.value, 10);

            if (isNaN(quantity) || quantity <= 0) {
                alert('Số lượng không hợp lệ. Vui lòng nhập số lượng lớn hơn 0.');
                event.preventDefault();
                return;
            }

            if (quantity > availableQuantity) {
                alert('Số lượng yêu cầu vượt quá số lượng có sẵn trong kho.');
                event.preventDefault();
                return;
            }

            // Update the hidden input with structured items data
            const itemsData = [{
                size_id: sizeId,
                quantity: quantity
            }];
            document.getElementById('items-input').value = JSON.stringify(itemsData);

            console.log('Mua Ngay: Items =', itemsData);
        });


        document.querySelector('.add-cart-btn').addEventListener('click', function(event) {
            // Find the active size button
            const selectedSize = document.querySelector('.size-btn.active');
            if (!selectedSize) {
                alert('Vui lòng chọn size trước khi thêm vào giỏ hàng!');
                event.preventDefault(); // Prevent the form from being submitted if size is not selected
                return;
            }

            // Get the size ID and available quantity
            const sizeId = selectedSize.getAttribute('data-size');
            const availableQuantity = parseInt(selectedSize.getAttribute('data-quantity'), 10);

            // Get the quantity entered by the user
            const quantityInput = document.getElementById('quantity');
            const quantity = parseInt(quantityInput.value, 10);

            // Check if the entered quantity is valid
            if (isNaN(quantity) || quantity <= 0) {
                alert('Số lượng không hợp lệ. Vui lòng nhập số lượng lớn hơn 0.');
                event.preventDefault(); // Prevent the form from being submitted if the quantity is invalid
                return;
            }

            // Check if the requested quantity exceeds available stock
            if (quantity > availableQuantity) {
                alert('Số lượng yêu cầu vượt quá số lượng có sẵn trong kho.');
                event.preventDefault(); // Prevent the form from being submitted if the quantity exceeds stock
                return;
            }

            // Update the hidden input fields with the selected size ID and quantity
            document.getElementById('size_id').value = sizeId;
            document.getElementById('quantity_input').value = quantity;
        });


        // function buyProduct() {
        //     const sizeId = document.getElementById('size_id').value;
        //     const quantity = document.getElementById('quantity').value;
        //     if (!sizeId) {
        //         alert('Vui lòng chọn size trước khi mua!');
        //         return;
        //     }
        //     alert(`Mua ngay: Size ${sizeId}, Số lượng: ${quantity}`);
        // }


        // Thay đổi hình ảnh chính khi nhấn vào hình nhỏ
        function changeImage(thumbnail) {
            const mainImage = document.getElementById('main-image');
            mainImage.src = thumbnail.src;
        }
    </script>
    <style>


    </style>
</body>

</html>