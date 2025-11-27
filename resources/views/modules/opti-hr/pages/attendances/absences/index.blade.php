@extends('modules.opti-hr.pages.base')

@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/absences.css') }}">
@endsection

@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div class="card-header p-0 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0">Gestion des Absences</h3>
                <div class="d-flex py-2 project-tab flex-wrap w-sm-100">
                    <a role="button" href="{{ route('absences.create') }}" class="btn btn-dark w-sm-100">
                        <i class="icofont-plus-circle me-2 fs-6"></i>Nouvelle demande
                    </a>

                    {{-- Nouveau système de 3 tabs --}}
                    <ul class="nav nav-tabs tab-body-header rounded ms-3 prtab-set w-sm-100" role="tablist">
                        {{-- Tab 1: À traiter (PENDING + IN_PROGRESS) --}}
                        <li class="nav-item">
                            <a class="nav-link {{ in_array($stage, ['PENDING', 'IN_PROGRESS', 'TO_PROCESS']) ? 'active' : '' }}"
                               href="{{ route('absences.requests', 'TO_PROCESS') }}" role="tab">
                                <i class="bi bi-clock-history me-1"></i>
                                À traiter
                                @if(isset($pendingCount) && $pendingCount > 0)
                                    <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </li>

                        {{-- Tab 2: Historique (APPROVED + REJECTED) --}}
                        <li class="nav-item">
                            <a class="nav-link {{ in_array($stage, ['APPROVED', 'REJECTED', 'HISTORY']) ? 'active' : '' }}"
                               href="{{ route('absences.requests', 'HISTORY') }}" role="tab">
                                <i class="bi bi-archive me-1"></i>
                                Historique
                            </a>
                        </li>

                        {{-- Tab 3: Annulées --}}
                        <li class="nav-item">
                            <a class="nav-link {{ $stage === 'CANCELLED' ? 'active' : '' }}"
                               href="{{ route('absences.requests', 'CANCELLED') }}" role="tab">
                                <i class="bi bi-x-circle me-1"></i>
                                Annulées
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Affichage conditionnel selon le stage --}}
    @if (in_array($stage, ['APPROVED', 'REJECTED', 'HISTORY', 'CANCELLED', 'COMPLETED', 'ALL']))
        {{-- Historique et Annulées: Tableau DataTable --}}
        @include('modules.opti-hr.pages.attendances.absences.handled-requests')
    @else
        {{-- À traiter: Cartes Accordion avec Stepper --}}
        @include('modules.opti-hr.pages.attendances.absences.unhandled-requests')
    @endif
@endsection

@push('plugins-js')
    <script src="{{ asset('assets/bundles/dataTables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ asset('app-js/attendances/absences/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
    <script src="{{ asset('app-js/filter/filter.js') }}"></script>
@endpush
