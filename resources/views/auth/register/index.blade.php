@extends('auth.base')

@section('auth-content')
    <form class="row g-1 p-3 p-md-4">
        <div class="col-12 text-center mb-1 mb-lg-5">
            <h1>Create your account</h1>
            <span>Free access to our dashboard.</span>
        </div>
        <div class="col-6">
            <div class="mb-2">
                <label class="form-label">Full name</label>
                <input type="email" class="form-control form-control-lg" placeholder="John">
            </div>
        </div>
        <div class="col-6">
            <div class="mb-2">
                <label class="form-label">&nbsp;</label>
                <input type="email" class="form-control form-control-lg" placeholder="Parker">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control form-control-lg" placeholder="name@example.com">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <label class="form-label">Password</label>
                <input type="email" class="form-control form-control-lg" placeholder="8+ characters required">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-2">
                <label class="form-label">Confirm password</label>
                <input type="email" class="form-control form-control-lg" placeholder="8+ characters required">
            </div>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    I accept the <a href="#" title="Terms and Conditions" class="text-secondary">Terms and
                        Conditions</a>
                </label>
            </div>
        </div>
        <div class="col-12 text-center mt-4">
            <a href="auth-signin.html" class="btn btn-lg btn-block btn-light lift text-uppercase" alt="SIGNUP">SIGN UP</a>
        </div>
        <div class="col-12 text-center mt-4">
            <span class="text-muted">Already have an account? <a href="auth-signin.html" title="Sign in"
                    class="text-secondary">Sign in here</a></span>
        </div>
    </form>
@endsection
@push('js')
@endpush
