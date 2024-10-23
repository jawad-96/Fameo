<div class="main-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container position-relative">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo">
                </a>
                <div class="mobile-menu-right">
                    <div class="mobile-menu-btn">
                        <a href="#" class="nav-right-link search-box-outer"><i class="far fa-search"></i></a>
                        <a href="{{ url('wishlist') }}" class="nav-right-link"><i class="far fa-heart"></i><span>2</span></a>
                        <a href="{{ url('shop-cart') }}" class="nav-right-link"><i class="far fa-shopping-bag"></i><span>5</span></a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
                
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <a href="{{ url('/') }}" class="offcanvas-brand" id="offcanvasNavbarLabel">
                            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo">
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown">Home</a>
                                <ul class="dropdown-menu fade-down">
                                    <li><a class="dropdown-item" href="{{ url('/') }}">Home Demo 01</a></li>
                                    <li><a class="dropdown-item" href="{{ url('index-2') }}">Home Demo 02</a></li>
                                    <li><a class="dropdown-item" href="{{ url('index-3') }}">Home Demo 03</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('about') }}">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('shop') }}">Shop</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('contact') }}">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>