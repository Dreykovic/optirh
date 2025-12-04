@extends('base')

@section('content')
    <div class="main p-2 py-3 p-xl-5">
        <div class="body d-flex p-0 p-xl-5">
            <div class="container-xxl">
                <div class="row g-0">
                    {{-- Colonne gauche - Branding (visible uniquement sur desktop) --}}
                    <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center rounded-lg auth-h100">
                        <div style="max-width: 25rem;">
                            <div class="text-center mb-5">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="OptiRH Logo">
                            </div>
                            <div class="mb-5">
                                <h2 class="color-900 text-center">OptiRh</h2>
                            </div>
                            <div class="">
                                <img src="{{ asset('assets/images/login-img.svg') }}" alt="Illustration">
                            </div>
                        </div>
                    </div>

                    {{-- Colonne droite - Contenu d'erreur --}}
                    <div class="col-lg-6 d-flex justify-content-center align-items-center border-0 rounded-lg auth-h100">
                        <div class="w-100 p-3 p-md-5 card border-0 bg-dark text-light" style="max-width: 32rem;">
                            <div class="row g-1 p-3 p-md-4">
                                <div class="col-12 text-center mb-4">
                                    {{-- Icône d'erreur --}}
                                    <div class="mb-4">
                                        @yield('error-icon')
                                    </div>

                                    {{-- Code et titre d'erreur --}}
                                    <h2 class="text-light mb-2">
                                        <span class="fw-bold">@yield('error-code')</span>
                                    </h2>
                                    <h5 class="text-light mb-3">@yield('error-title')</h5>

                                    {{-- Message descriptif --}}
                                    <p class="text-light opacity-75 mb-4">
                                        @yield('error-message')
                                    </p>
                                </div>

                                {{-- Boutons de navigation --}}
                                <div class="col-12 text-center">
                                    <a href="{{ url('/') }}" class="btn btn-lg btn-light lift text-uppercase">
                                        <i class="icofont-ui-home me-2"></i>Retourner à l'Accueil
                                    </a>
                                    @hasSection('error-action')
                                        <div class="mt-3">
                                            @yield('error-action')
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
