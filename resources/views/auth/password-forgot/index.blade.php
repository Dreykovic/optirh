@extends('auth.base')

@section('auth-content')
    <form class="row g-1 p-3 p-md-4" id="modelAddForm" data-model-add-url="{{ route('send.mail') }}">
        <div class="col-12 text-center mb-1 mb-lg-5">
            {{-- <img src="{{ asset('assets/images/forgot-password.svg') }}" class="w240 mb-4" alt="" /> --}}
            <h1>Mot De Passe Oublié ?</h1>
            <span>Entrez l'adresse e-mail que vous avez utilisée lors de votre inscription et nous vous enverrons des
                instructions pour réinitialiser votre
                mot de passe.</span>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <label class="form-label">Email </label>
                <input type="email" class="form-control form-control-lg" placeholder="name@example.com" autocomplete="off"
                    name="email">
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
            <span class="text-muted"><a href="{{ route('login') }}" class="text-secondary">Back to Sign in</a></span>
        </div>
    </form>
@endsection
@push('js')
    <script src={{ asset('app-js/crud/post.js') }}></script>
@endpush
