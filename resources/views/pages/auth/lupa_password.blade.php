<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>GS - Lupa Password</title>
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
                            <img src="{{ asset('assets/images/logo/GS.png')}}" class="img mb-20" alt="" srcset="">
                            <div class="content">
                                <h1 style="font-weight: 400">Lupa Password?</h1>
                                <p>Masukkan email terdaftar Anda di bawah ini untuk menerima instruksi reset kata sandi.</p>
                            </div>

                            <div class="post-email-form">
                                <div class="row">
                                    <div class="col-12 mb-20"><input class="form-control" type="text" placeholder="Email"></div>
                                    <div class="col-12 mt-10"><button id="postLink" class="button button-primary text-white">Kirim</button></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="login-register-bg order-1 order-lg-2 col-lg-7 col-12">
                        <div class="content">
                            <h1 style="font-weight: 400">Lupa Password?</h1>
                            <p>Masukkan email terdaftar Anda di bawah ini untuk menerima instruksi reset kata sandi.</p>
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!--Main JS-->
    <script src="assets/js/main.js"></script>

    <script>
        $(document).ready(function () {
            $('#postLink').on('click', () => {
                swal({
                    title : 'Periksa Email Anda',
                    text : 'Kami telah mengirimkan instruksi pemulihan kata sandi ke email Anda',
                    icon : 'success'
                }).then(function () {window.location = '/password-baru';});
            })
        });
    </script>

</body>

</html>
