@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header p-0 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0">Demandes Absences</h3>
                <div class="d-flex py-2 project-tab flex-wrap w-sm-100">
                    <button type="button" class="btn btn-dark w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#createAbsence"><i class="icofont-plus-circle me-2 fs-6"></i>Créer</button>
                    <ul class="nav nav-tabs tab-body-header rounded ms-3 prtab-set w-sm-100" role="tablist">
                        <li class="nav-item"><a class="nav-link {{ $stage === 'ALL' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'ALL') }}" role="tab">Toutes</a></li>

                        <li class="nav-item"><a
                                class="nav-link {{ $stage === 'PENDING' || $stage === 'PENDING' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'PENDING') }}" role="tab">Nouvelles</a></li>
                        <li class="nav-item"><a
                                class="nav-link {{ $stage === 'IN_PROGRESS' || $stage === 'IN_PROGRESS' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'IN_PROGRESS') }}" role="tab">En
                                Cours De Traitement</a></li>

                        <li class="nav-item"><a class="nav-link {{ $stage === 'APPROVED' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'APPROVED') }}" role="tab">Accordées</a></li>
                        <li class="nav-item"><a class="nav-link {{ $stage === 'REJECTED' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'REJECTED') }}" role="tab">Rejetées</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->

    @if (in_array($stage, ['APPROVED', 'REJECTED', 'CANCELLED', 'COMPLETED', 'ALL']))
        <!-- Le stage est dans la liste -->
        @include('pages.admin.attendances.absences.handled-requests')
    @else
        <!-- Le stage n'est pas dans la liste -->
        @include('pages.admin.attendances.absences.unhandled-requests')
    @endif
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/attendances/absences/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
    <script src="{{ asset('app-js/filter/filter.js') }}"></script>
@endpush
