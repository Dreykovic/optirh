<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ARCOP-MANAGER</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> <!-- Favicon-->

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @yield('plugins-style')
    <!-- project css file  -->
    <link rel="stylesheet" href="{{ asset('assets/css/my-task.style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-enhanced.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @stack('css')

</head>

<body>
    @guest
        <style>
            .background-animation {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(-45deg, #d6e6d9, #b5d4b8, #629d72, #82b690);
                background-size: 400% 400%;
                animation: gradientAnimation 10s ease infinite;
                z-index: -1;
            }

            @keyframes gradientAnimation {
                0% {
                    background-position: 0% 50%;
                }

                50% {
                    background-position: 100% 50%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }
        </style>
        <div class="background-animation">

        </div>
    @endguest


    <div id="mytask-layout" class="theme-indigo">

        @yield('content')

    </div>

    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>



    <!-- Plugin Js-->
    @stack('plugins-js')

    <!-- Jquery Page Js -->
    <script src="{{ asset('assets/js/custom/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/axios.min.js') }}"></script>
    <script src="{{ asset('app-js/modules.js') }}"></script>
    @stack('js')
    <script src="{{ asset('assets/js/custom/script.js') }}"></script>

</body>

</html>
