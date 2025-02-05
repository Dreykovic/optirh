@extends('pages.auth.base')

@section('auth-content')
    <form class="row g-1 p-3 p-md-4" id="modelAddForm" data-model-add-url="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="col-12 text-center mb-1 mb-lg-5">
            <h1>Réinitialiser Mot De Passe</h1>

        </div>
        <div class="col-12">
            <div class="mb-2">
                <label class="form-label">Adresse Email</label>
                <input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <label class="form-label">Nouveau Mot De Passe</label>
                <input type="password" class="form-control form-control-lg" placeholder="***************" name="password">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <div class="form-label">
                    <span class="d-flex justify-content-between align-items-center">
                        Confirmer le mot de passe

                    </span>
                </div>
                <input type="password" class="form-control  form-control-lg" autocomplete="off" name="password_confirmation"
                    placeholder="***************">
            </div>
        </div>

        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-lg btn-block btn-light lift text-uppercase" atl="signin"
                id="modelAddBtn">
                <span class="normal-status">
                    Soumettre
                </span>
                <span class="indicateur d-none">
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Un Instant...
                </span>
            </button>
        </div>
        <div class="col-12 text-center mt-4">
            <span class="text-muted"> Déjà fait ?

                <a href="{{ route('login') }}" class="text-secondary">Connectez-vous</a></span>
        </div>
    </form>
@endsection
@push('js')
    <script src={{ asset('app-js/crud/post.js') }}></script>
@endpush
