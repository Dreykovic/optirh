@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/intl-tel-input/css/intlTelInput.min.css') }}">
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 ">
            <div
                class="card-header p-0 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0"> </h3>
                <div class="d-flex py-2 project-tab flex-wrap w-sm-100">

                    <ul class="nav nav-tabs tab-body-header rounded ms-3 prtab-set w-sm-100" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#details" role="tab">Infos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-bs-toggle="tab" href="#accounts" role="tab">Comptes
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->
    <div class="row align-items-center">
        <div class="col-lg-12 col-md-12 flex-column">
            <div class="tab-content mt-4">
                <div class="tab-pane fade show active" id="details">
                    @include('pages.admin.client.details')
                </div>
                <div class="tab-pane fade  " id="accounts">
                    @include('pages.admin.client.accounts')
                </div>

            </div>
        </div>
    </div>
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script>
    <script src="{{ asset('assets/plugins/intl-tel-input/js/intlTelInput.min.js') }}"></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/account/table.js') }}"></script>

    <script src={{ asset('app-js/crud/post.js') }}></script>
    <script src={{ asset('app-js/crud/put.js') }}></script>
@endpush
