<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>SETKON - @yield('title')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets2') }}/img/favicon.png" rel="icon">
    <link href="{{ asset('assets2') }}/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/5983388006.js" crossorigin="anonymous"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets2') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets2') }}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets2') }}/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="{{ asset('assets2') }}/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="{{ asset('assets2') }}/vendor/aos/aos.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets2') }}/css/main.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Append
  * Updated: Jul 27 2023 with Bootstrap v5.3.1
  * Template URL: https://bootstrapmade.com/append-bootstrap-website-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page" data-bs-spy="scroll" data-bs-target="#navmenu">

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="container-fluid d-flex align-items-center justify-content-between">

            <a href="{{ route('home.index') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1>SETKON</h1>
                <span>.</span>
            </a>

            <!-- Nav Menu -->
            <nav id="navmenu" class="navmenu">
                {{-- <ul>
                    <li><a href="{{ route('home.index') }}" class="active">Home</a></li>
                    <li><a href="index.html#about">About</a></li>
                    <li><a href="index.html#services">Services</a></li>
                    <li><a href="index.html#portfolio">Portfolio</a></li>
                    <li><a href="index.html#team">Team</a></li>
                    <li><a href="blog.html">Blog</a></li>
                    <li class="dropdown has-dropdown"><a href="#"><span>Dropdown</span> <i
                                class="bi bi-chevron-down"></i></a>
                        <ul class="dd-box-shadow">
                            <li><a href="#">Dropdown 1</a></li>
                            <li class="dropdown has-dropdown"><a href="#"><span>Deep Dropdown</span> <i
                                        class="bi bi-chevron-down"></i></a>
                                <ul class="dd-box-shadow">
                                    <li><a href="#">Deep Dropdown 1</a></li>
                                    <li><a href="#">Deep Dropdown 2</a></li>
                                    <li><a href="#">Deep Dropdown 3</a></li>
                                    <li><a href="#">Deep Dropdown 4</a></li>
                                    <li><a href="#">Deep Dropdown 5</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Dropdown 2</a></li>
                            <li><a href="#">Dropdown 3</a></li>
                            <li><a href="#">Dropdown 4</a></li>
                        </ul>
                    </li>
                    <li><a href="index.html#contact">Contact</a></li>
                </ul> --}}

                <i class="mobile-nav-toggle d-xl-none bi bi-list" hidden></i>
            </nav><!-- End Nav Menu -->
            @guest
                <a class="btn-getstarted" href="{{ route('login') }}">Login</a>
            @else
                <a href="{{ route('dashboard.index') }}" class="btn-getstarted">My Dashboard</a>
            @endguest


        </div>
    </header><!-- End Header -->

    @yield('content')

    <!-- Scroll Top Button -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets2') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets2') }}/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="{{ asset('assets2') }}/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="{{ asset('assets2') }}/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="{{ asset('assets2') }}/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets2') }}/vendor/aos/aos.js"></script>
    <script src="{{ asset('assets2') }}/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets2') }}/js/main.js"></script>

</body>

</html>
