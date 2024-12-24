@extends('base')
@section('content')
    @guest

        <!-- main body area -->
        <div class="main p-2 py-3 p-xl-5 ">

            <!-- Body: Body -->
            <div class="body d-flex p-0 p-xl-5">
                <div class="container-xxl">

                    <div class="row g-0">
                        <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center rounded-lg auth-h100">
                            <div style="max-width: 25rem;">
                                <div class="text-center mb-5">

                                    <img src="{{ asset('assets/img/logo.png') }}" alt="">

                                </div>
                                <h1 class="text-center ">OptiRH</h1>
                                <div class="mb-5">
                                    <h2 class="color-900 text-center">

                                        Gestion fluide des congés et des attendances de vos employées
                                    </h2>
                                </div>
                                <!-- Image block -->
                                <div class="">
                                    <img src="../assets/images/login-img.svg" alt="login-img">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-center align-items-center border-0 rounded-lg auth-h100">
                            <div class="w-100 p-3 p-md-5 card border-0 bg-dark text-light" style="max-width: 32rem;">
                                <!-- Form -->
                                @yield('auth-content')
                                <!-- End Form -->
                            </div>
                        </div>
                    </div> <!-- End Row -->
                </div>
            </div>
        </div>

    @endguest
@endsection
