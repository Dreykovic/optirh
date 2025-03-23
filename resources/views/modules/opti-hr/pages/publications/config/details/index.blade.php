@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0">Détails Rôle : <span class="text-primary"> {{ $role->name }}</span></h3>
                <div class="d-flex py-2 project-tab flex-wrap w-sm-100">
                    <ul class="nav nav-tabs tab-body-header rounded ms-3 prtab-set w-sm-100" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#list-view"
                                role="tab">Aperçu</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#grid-view"
                                role="tab">Paramètres</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="list-view">
            @include('modules.opti-hr.pages.users.roles.details.overview')
        </div>
        <div class="tab-pane fade" id="grid-view">
            @include('modules.opti-hr.pages.users.roles.details.settings')

        </div>
    </div>
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/users/credentials/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
