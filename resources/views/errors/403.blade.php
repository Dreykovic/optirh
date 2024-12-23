@extends('errors.base')

@section('errors-content')
    <div class="col-lg-6 d-flex justify-content-center align-items-center border-0 rounded-lg auth-h100">
        <div class="w-100 p-3 p-md-5 card border-0 bg-dark text-light" style="max-width: 32rem;">
            <!-- Form -->
            <form class="row g-1 p-3 p-md-4">
                <div class="col-12 text-center mb-1 mb-lg-5">
                    <img src="../assets/images/not_found.svg" class="w240 mb-4" alt="" />
                    <h5>OOP! 403</h5>
                    <span class="text-light">Sorry, the page you're looking for doesn;t exist. if you think
                        something is brlken, report a problem.</span>
                </div>
                <div class="col-12 text-center">
                    <a href="index.html" title="" class="btn btn-lg btn-block btn-light lift text-uppercase">Back to
                        Home</a>
                </div>
            </form>
            <!-- End Form -->
        </div>
    </div>
@endsection
