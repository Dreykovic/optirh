@extends('auth.base')

@section('auth-content')
    <form class="row g-1 p-3 p-md-4" id="modelAddForm" data-model-add-url="{{ route('login') }}">
        @csrf
        <div class="col-12 text-center mb-1 mb-lg-5">
            <h1>Connexion</h1>
            <span>Free access to our dashboard.</span>
        </div>

        <div class="col-12">
            <div class="mb-2">
                <label class="form-label">Adresse Email</label>
                <input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <div class="form-label">
                    <span class="d-flex justify-content-between align-items-center">
                        Mot De Passe
                        <a class="text-secondary" href="{{ route('forgot-password') }}">Mot de passe oublié??</a>
                    </span>
                </div>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="***************">
            </div>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="remember">
                <label class="form-check-label" for="flexCheckDefault">
                    Se souvenir de moi
                </label>
            </div>
        </div>
        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-lg btn-block btn-light lift text-uppercase" atl="signin"
                id="modelAddBtn">
                <span class="normal-status">
                    Se
                    Connecter
                </span>
                <span class="indicateur d-none">
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Un Instant...
                </span>
            </button>


        </div>

    </form>
@endsection
@push('js')
    <script src={{ asset('app-js/crud/post.js') }}></script>
@endpush
