@extends('base')
@section('content')
    <!-- main body area -->
    <div class="main p-2 py-3 p-xl-5">

        <!-- Body: Body -->
        <div class="body d-flex p-0 p-xl-5">
            <div class="container-xxl">

                <div class="row g-0">
                    <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center rounded-lg auth-h100">
                        <div style="max-width: 25rem;">
                            <div class="text-center mb-5">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="">
                            </div>
                            <div class="mb-5">
                                <h2 class="color-900 text-center">OptiRh</h2>
                            </div>
                            <!-- Image block -->
                            <div class="">
                                <img src={{ asset('assets/images/login-img.svg') }} alt="login-img">
                            </div>
                        </div>
                    </div>

                    @yield('errors-content')
                </div> <!-- End Row -->

            </div>
        </div>

    </div>
@endsection
