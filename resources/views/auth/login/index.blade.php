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
            <input type="password" class="form-control form-control-lg" id="passwordInput" name="password" required>
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
@endsection

@push('js')
    <script src="{{ asset('app-js/auth/login.js') }}"></script>
@endpush
