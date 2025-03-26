@extends('auth.base')

@section('auth-content')
    <form id="modelAddForm" data-model-add-url="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="text-center mb-4">
            <h4 class="fw-bold">Réinitialiser Mot De Passe</h4>
        </div>

        <div class="mb-3">
            <label for="emailInput" class="form-label">Adresse Email</label>
            <input type="email" class="form-control form-control-lg" id="emailInput" name="email"
                placeholder="name@example.com" required>
        </div>

        <div class="mb-3">
            <label for="passwordInput" class="form-label">Nouveau Mot De Passe</label>
            <input type="password" class="form-control form-control-lg" id="passwordInput" name="password"
                placeholder="***************" required>
        </div>

        <div class="mb-4">
            <label for="passwordConfirmInput" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control form-control-lg" id="passwordConfirmInput"
                name="password_confirmation" placeholder="***************" required>
        </div>

        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-dark btn-lg" id="modelAddBtn">
                <span class="normal-status">
                    Soumettre
                </span>
                <span class="indicateur d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Un instant...
                </span>
            </button>
        </div>

        <div class="text-center mt-3">
            <span class="text-muted">Déjà fait ? <a href="{{ route('login') }}"
                    class="text-decoration-none">Connectez-vous</a></span>
        </div>
    </form>
@endsection

@push('js')
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
@endpush
