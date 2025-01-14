<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" href="/assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/templatemo.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">

    <style>
        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
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

                    <!-- login -->

                    <a class="nav-icon position-relative text-decoration-none" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                    </a>

                    <!-- Dropdown -->
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        @auth
                        <div class="dropdown-item"> Hi, {{ Auth::user()->name }}</div>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            {{ __('Profile') }}
                        </a>
                        <a class="dropdown-item" href="{{ Auth::user()->role == 'admin' ? url('/admin/dashboard') : url('/') }}">
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

    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">
            <div class="login-container">
                <h2 class="text-center mb-4">Login</h2>
                <!-- Form sử dụng phương thức POST và route login -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <label for="remember_me" class="form-check-label">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <x-primary-button class="btn btn-primary">{{ __('Log in') }}</x-primary-button>
                    </div>

                    <!-- Forgot Password Link -->
                    @if (Route::has('password.request'))
                    <p class="mt-3 text-center">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">{{ __('Forgot your password?') }}</a>
                    </p>
                    @endif

                    <!-- Register Link -->
                    @if (Route::has('register'))
                    <p class="text-center">
                        {{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="text-decoration-none">{{ __('Register') }}</a>
                    </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <!-- End Content -->




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>