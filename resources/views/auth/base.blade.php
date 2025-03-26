@extends('base')
@section('content')
    @guest
        <div class="container-fluid p-0">
            <div class="row g-0 min-vh-100">
                <!-- Left side (logo) - Fixed height and scrollable if needed -->
                <div class="col-lg-6 bg-light d-none d-lg-block">
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 p-5">
                        <div class="text-center mb-5">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="OptiRH Logo" class="img-fluid mb-4"
                                style="max-height: 80px; width: auto;">
                            <h2 class="mb-4">ARCOP MAN</h2>
                        </div>
                        <div class="w-75">
                            <img src="{{ asset('assets/images/login-img.svg') }}" alt="OptiRH" class="img-fluid">
                        </div>
                    </div>
                </div>

                <!-- Right side (form) - Fixed position and consistent padding -->
                <div class="col-lg-6 bg-white">
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 p-4">
                        <!-- Mobile logo -->
                        <div class="d-block d-lg-none text-center mb-4 w-100">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="OptiRH Logo" class="img-fluid mb-3"
                                style="max-height: 60px; width: auto;">
                            <h3>OptiRH</h3>
                        </div>

                        <!-- Login form container with fixed width -->
                        <div class="card shadow-sm w-100" style="max-width: 400px;">
                            <div class="card-body p-4">
                                @yield('auth-content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest
@endsection
