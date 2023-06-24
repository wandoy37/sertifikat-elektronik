<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>SETKO - @yield('title')</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('assets') }}/img/icon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets') }}/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ['{{ asset('assets') }}/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/atlantis.css">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/demo.css">
</head>

<body>
    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="blue2">

                <a href="index.html" class="logo">
                    <span class="text-light fw-bold">
                        <div class="text-center">
                            SETKO BPPSDMP
                        </div>
                    </span>
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            @include('dashboard.layouts.partials.navbar')
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        @include('dashboard.layouts.partials.sidebar')
        <!-- End Sidebar -->

        <div class="main-panel">

            {{-- Section content --}}
            <div class="container">
                @yield('content')
            </div>
            {{-- /Section content --}}

            <footer class="footer">
                {{-- Section Footer --}}
                <div class="container-fluid">
                    <div class="copyright ml-auto">
                        {{ date('Y') }}, made with <i class="fa fa-heart heart text-danger"></i> by
                        <span class="text-secondary">Muhammad Riswandi</span>
                    </div>
                </div>
                {{-- /Section Footer --}}
            </footer>
        </div>
    </div>

    @stack('scripts')

    <!--   Core JS Files   -->
    <script src="{{ asset('assets') }}/js/core/jquery.3.2.1.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="{{ asset('assets') }}/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets') }}/js/plugin/datatables/datatables.min.js"></script>

    <!-- DateTimePicker -->
    <script src="{{ asset('assets') }}/js/plugin/datepicker/bootstrap-datetimepicker.min.js"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets') }}/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Atlantis JS -->
    <script src="{{ asset('assets') }}/js/atlantis.min.js"></script>
</body>

</html>
