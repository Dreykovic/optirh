@extends('pages.admin.base')
@section('admin-content')
    <div class="body d-flex py-lg-3 py-md-2">
        <div class="container-xxl">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body text-center p-5">
                        <img src="{{ asset('assets/images/no-data.svg') }}" class="img-fluid mx-size" alt="No Data">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('plugins-js')
    <script src="{{ asset('assets/bundles/apexcharts.bundle.js') }}"></script>
@endpush
@push('js')
    <script src="{{ asset('assets/js/page/hr.js') }}"></script>
@endpush
