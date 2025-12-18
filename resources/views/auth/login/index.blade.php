@extends('auth.base')

@section('auth-content')
    <form id="loginForm" data-login-url="{{ route('login') }}">
        @csrf
        <div class="text-center mb-4">
            <h4 class="fw-bold">Connexion</h4>
        </div>

        <div class="mb-3">
            <label for="emailInput" class="form-label">Adresse Email</label>
            <input type="email" class="form-control form-control-lg" id="emailInput" name="email" required>
        </div>

        <div class="mb-3">
            <label for="passwordInput" class="form-label">Mot de passe</label>
            <div class="input-group">
                <input type="password" class="form-control form-control-lg" id="passwordInput" name="password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Afficher/Masquer">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                    <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="display: none;">
                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                        <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberCheck" name="remember">
                    <label class="form-check-label" for="rememberCheck">
                        Se souvenir de moi
                    </label>
                </div>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('forgot-password') }}" class="text-decoration-none">Mot de passe oublié?</a>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-dark btn-lg" id="loginBtn">
                <span class="normal-status">Se connecter</span>
                <span class="indicateur d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Un instant...
                </span>
            </button>
        </div>
    </form>

    @if(app()->environment('local'))
        <div class="mt-4 pt-4 border-top">
            <p class="text-muted text-center mb-3">
                <small>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: -2px;">
                        <path d="M5.52.359A.5.5 0 0 1 6 0h4a.5.5 0 0 1 .474.658L8.694 6H12.5a.5.5 0 0 1 .395.807l-7 9a.5.5 0 0 1-.873-.454L6.823 9.5H3.5a.5.5 0 0 1-.48-.641l2.5-8.5z"/>
                    </svg>
                    Connexion rapide (Dev)
                </small>
            </p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <button type="button" class="btn btn-outline-danger btn-sm quick-login-btn"
                    data-email="dreybirewa@gmail.com" data-password="Admin@2024">
                    Admin
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm quick-login-btn"
                    data-email="amonaaudrey16@gmail.com" data-password="Grh@2024">
                    GRH
                </button>
                <button type="button" class="btn btn-outline-success btn-sm quick-login-btn"
                    data-email="codeurspassiones@gmail.com" data-password="Dg@2024">
                    DG
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm quick-login-btn"
                    data-email="amonaaudrey@hotmail.com" data-password="Dsaf@2024">
                    DSAF
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm quick-login-btn"
                    data-email="employee1@optirh.com" data-password="Employee@2024">
                    Employee
                </button>
            </div>
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('app-js/auth/login.js') }}"></script>
@endpush
