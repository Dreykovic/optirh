@extends('auth.base')

@section('auth-content')
    <form id="modelAddForm" data-model-add-url="{{ route('send.mail') }}">
        @csrf
        <div class="text-center mb-4">
            <h4 class="fw-bold">Mot De Passe Oublié ?</h4>
            <p class="text-muted small">
                Entrez l'adresse e-mail que vous avez utilisée lors de votre inscription et nous vous enverrons des
                instructions pour réinitialiser votre mot de passe.
            </p>
        </div>

        <div class="mb-4">
            <label for="emailInput" class="form-label">Adresse Email</label>
            <input type="email" class="form-control form-control-lg" id="emailInput" name="email"
                placeholder="name@example.com" required>
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
            <a href="{{ route('login') }}" class="text-decoration-none">Retour à la connexion</a>
        </div>
    </form>
@endsection

@push('js')
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
@endpush
