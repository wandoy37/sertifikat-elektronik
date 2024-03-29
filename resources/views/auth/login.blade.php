<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login</title>
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
</head>

<body class="login">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <h3 class="text-center">Login</h3>
            @if ($errors->any())
                <div class="mb-4 text-center">
                    <span class="text-warning">
                        <b>Login gagal, silahkan coba kembali !</b>
                    </span>
                </div>
            @endif
            <form action="{{ 'login' }}" method="post">
                @csrf
                <div class="login-form">
                    <div class="form-group form-floating-label">
                        <input id="username" name="username" type="text" class="form-control input-border-bottom"
                            required>
                        <label for="username" class="placeholder">Username</label>
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="password" name="password" type="password" class="form-control input-border-bottom"
                            required>
                        <label for="password" class="placeholder">Password</label>
                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>
                    </div>
                    {{-- <div class="mb-4">
                        <a href="#" class="link float-right">Lupa Password ?</a>
                    </div> --}}
                    <div class="form-action mb-3">
                        <button type="submit" class="btn btn-primary btn-rounded btn-login">Masuk</button>
                    </div>
                    {{-- <div class="login-account">
                        <a href="#daftar" id="show-signup" class="link">Belum punya akun ?</a>
                    </div> --}}
                </div>
            </form>
        </div>

        <div class="container container-signup animated fadeIn">
            <h3 class="text-center">Register</h3>
            <form action="{{ route('register') }}" method="post">
                @csrf
                <div class="login-form">
                    <div class="form-group form-floating-label">
                        <input id="username" name="username" type="text" class="form-control input-border-bottom"
                            required>
                        <label for="username" class="placeholder">Username</label>
                        @error('username')
                            <span class="text-warning">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="email" name="email" type="email" class="form-control input-border-bottom"
                            required>
                        <label for="email" class="placeholder">Email</label>
                        @error('email')
                            <span class="text-warning">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="passwordsignin" name="password" type="password"
                            class="form-control input-border-bottom" required>
                        <label for="passwordsignin" class="placeholder">Password</label>
                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>
                        @error('password')
                            <span class="text-warning">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="form-group form-floating-label">
                        <input id="confirmpassword" name="password_confirmation" type="password"
                            class="form-control input-border-bottom" required>
                        <label for="confirmpassword" class="placeholder">Confirm Password</label>
                        <div class="show-password">
                            <i class="icon-eye"></i>
                        </div>
                    </div>
                    <div class="form-action">
                        <a href="#login" id="show-signin" class="btn btn-danger btn-link btn-login mr-3">Kembali</a>
                        <button type="submit" class="btn btn-primary btn-rounded btn-login">Daftar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('assets') }}/js/core/jquery.3.2.1.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('assets') }}/js/atlantis.min.js"></script>
</body>

</html>
