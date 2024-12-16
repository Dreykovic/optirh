@extends('pages.auth.base')

@section('auth-content')
    <form class="row g-1 p-3 p-md-4">
        <div class="col-12 text-center mb-1 mb-lg-5">
            <img src="../assets/images/forgot-password.svg" class="w240 mb-4" alt="" />
            <h1>Mot De Passe Oublié ?</h1>
            <span>Enter the email address you used when you joined and we'll send you instructions to reset your
                password.</span>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control form-control-lg" placeholder="name@example.com">
            </div>
        </div>
        <div class="col-12 text-center mt-4">
            <a href="auth-two-step.html" title=""
                class="btn btn-lg btn-block btn-light lift text-uppercase">SUBMIT</a>
        </div>
        <div class="col-12 text-center mt-4">
            <span class="text-muted"><a href="{{ route('login') }}" class="text-secondary">Back to Sign in</a></span>
        </div>
    </form>
@endsection
@push('js')
@endpush
