@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header p-0 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0">Demandes De Documents</h3>
                <div class="d-flex py-2 project-tab flex-wrap w-sm-100 gap-2">
                    <a role="button" href="{{ route('documents.create') }}" class="btn btn-dark w-sm-100">
                        <i class="icofont-plus-circle me-2 fs-6"></i>Créer
                    </a>
                    {{-- Navigation simplifiée : 3 tabs --}}
                    <ul class="nav nav-tabs tab-body-header rounded ms-lg-3 prtab-set w-sm-100" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $stage === 'ALL' ? 'active' : '' }}"
                               href="{{ route('documents.requests', 'ALL') }}" role="tab">
                                <i class="icofont-listine-dots me-1 d-none d-md-inline"></i>Toutes
                                <span class="badge bg-secondary ms-1">{{ $counts['all'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $stage === 'TO_PROCESS' ? 'active' : '' }}"
                               href="{{ route('documents.requests', 'TO_PROCESS') }}" role="tab">
                                <i class="icofont-clock-time me-1 d-none d-md-inline"></i>À Traiter
                                <span class="badge bg-warning text-dark ms-1">{{ $counts['to_process'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $stage === 'FINISHED' ? 'active' : '' }}"
                               href="{{ route('documents.requests', 'FINISHED') }}" role="tab">
                                <i class="icofont-check-circled me-1 d-none d-md-inline"></i>Terminées
                                <span class="badge bg-success ms-1">{{ $counts['finished'] ?? 0 }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->




    <!-- Le stage est dans la liste -->
    @include('modules.opti-hr.pages.documents.main.handled-requests')
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/documents/main/table.js') }}"></script>
    <script src="{{ asset('app-js/documents/main/actions.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
    <script src="{{ asset('app-js/filter/filter.js') }}"></script>
@endpush
