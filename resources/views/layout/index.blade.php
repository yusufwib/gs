<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.header', ['title' => $title])
    <!-- CUSTOM CSS -->
    @yield('css')
    {{-- CONST AUTH --}}
    <script src="{{ asset('assets/js/vendor/jquery-3.3.1.min.js')}}"></script>
    <script>
        $('html, body').css({ 'overflow': 'hidden', 'height': '100%'})

        var base_url = window.location.origin;
        let enpoint = base_url+'/api/v1/auth/user-profile';

        if (localStorage.getItem("user_token") === null) {
            window.location.href = '/login'
        }else{
            $.ajaxSetup({
                headers:  {"Authorization": 'Bearer '+localStorage.getItem('user_token')}
            });

            $.ajax({
                type: "GET",
                url: enpoint,
                success: function (response) {
                    $(".name").html(response.data.name);
                    $(".mail").html(response.data.email);
                },
                error: function (err) {
                    window.location.href = '/login'
                }
            });
        }
    </script>
</head>

<body>
<div class="main-wrapper">
    @include('layout.navbar')
    @include('layout.sidebar')
    <!-- Content Body Start -->
    <div class="content-body">
        <!-- Page Headings Start -->
        <div class="row justify-content-between align-items-center mb-10">
            <div class="col-12 col-lg-auto mb-20" >
                <!-- Page Heading Start -->
                <div class="page-heading">
                    <a id="prev_page" href="{{!empty($link) ? $link : ''}}" class="d-inline">{{ !empty($prev) ? $prev.' / ' : ' '}}</a><h3 class="d-inline">{{ $heading }}</h3>
                </div>
                <!-- Page Heading End -->
            </div>

            <div class="col-12 col-lg-auto mb-20 action-button">
                @yield('action-button')
            </div>
            <!-- Page Button Group Start -->
            {{-- <div class="col-12 col-lg-auto mb-20">
                <div class="page-date-range">
                    <input type="text" class="form-control input-date-predefined">
                </div>
            </div> --}}
            <!-- Page Button Group End -->
        </div><!-- Page Headings End -->
        <div class="spinner-bg"></div>
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
        @yield('contents')
    </div>

</div>
    @include('includes.footer')
    <!-- CUSTOM JS -->
    <script>
        $("#btn-logout").on('click', function () {
            localStorage.clear();
            window.location.href = '/login'
        });
        loadingFalse = () =>{
            $('html, body').removeAttr('style');
            $(".spinner").css("display","none");
            $(".spinner-bg").css("display","none");
        }

        loadingTrue = () =>{
            $('html, body').css({ 'overflow': 'hidden', 'height': '100%', 'cursor' : 'wait' })
            $(".spinner").css("display","block");
            $(".spinner-bg").css("display","block");
        }
    </script>
    @yield('js')
    @yield('modal')
</body>


</html>
