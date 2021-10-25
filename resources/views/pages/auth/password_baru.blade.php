<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>GS - Password Baru</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logo/icon.png')}}">
    <script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script>
        var base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/auth/user-profile';

        if (localStorage.getItem("user_token") !== null) {
            $.ajaxSetup({
                headers:  {"Authorization": 'Bearer '+localStorage.getItem('user_token')}
            });

            $.ajax({
                type: "GET",
                url: enpoint,
                success: function (response) {
                    window.location.href = '/dashboard'
                },
                error: function (err) {

                }
            });
        }
    </script>
    <!-- CSS
	============================================ -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="assets/css/vendor/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/vendor/themify-icons.css">
    <link rel="stylesheet" href="assets/css/vendor/cryptocurrency-icons.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/css/plugins/plugins.css">

    <!-- Helper CSS -->
    <link rel="stylesheet" href="assets/css/helper.css">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- CUSTOM Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">
</head>

<body>

    <div class="main-wrapper">

        <!-- Content Body Start -->
        <div class="content-body m-0 p-0">

            <div class="login-register-wrap">
                <div class="row">

                    <div class="d-flex align-self-center justify-content-center order-2 order-lg-1 col-lg-5 col-12">
                        <div class="login-register-form-wrap">
                            <img src="{{ asset('assets/images/logo/GS.png')}}" class="img mb-15" alt="" srcset="">
                            <div class="content">
                                <h1 style="font-weight: 400">Buat Password Baru</h1>
                                <p>Kata sandi baru Anda harus berbeda dari kata sandi yang digunakan sebelumnya.</p>
                            </div>
                            <div class="login-register-form">
                                <form action="#">
                                    <div class="row">
                                        <div class="col-12 mb-20"><input class="form-control" type="text" placeholder="Password"></div>
                                        <div class="col-12 mb-20"><input class="form-control" type="password" placeholder="Konfirmasi Password"></div>
                                        <div class="col-12 mt-10"><a href="/dashboard" class="button button-primary text-white">Buat</a></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="login-register-bg order-1 order-lg-2 col-lg-7 col-12">
                        <div class="content">
                            <h1 style="font-weight: 400">Buat Password Baru</h1>
                            <p>Kata sandi baru Anda harus berbeda dari kata sandi yang digunakan sebelumnya.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- Content Body End -->

    </div>

    <!-- JS
============================================ -->

    <!-- Global Vendor, plugins & Activation JS -->
    <script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="assets/js/vendor/popper.min.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <!--Plugins JS-->
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/tippy4.min.js.js"></script>
    <!--Main JS-->
    <script src="assets/js/main.js"></script>

</body>

</html>
