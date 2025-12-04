@extends('modules.opti-hr.pages.base')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
@endpush

@section('admin-content')
    <div class="dashboard-container">
        {{-- En-tete du dashboard --}}
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="dashboard-title">Tableau de bord</h1>
                    <p class="dashboard-subtitle mb-0">
                        Bienvenue, {{ auth()->user()->employee->first_name ?? auth()->user()->username }}
                    </p>
                </div>
                <div class="col-auto d-flex align-items-center gap-3">
                    <span class="dashboard-date d-none d-md-inline">
                        {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                    </span>
                    <button id="refreshDashboard" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip"
                        title="Actualiser le tableau de bord">
                        <i class="icofont-refresh"></i>
                        <span class="d-none d-sm-inline ms-1">Actualiser</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Contenu du dashboard selon le role --}}
        @include("modules.opti-hr.pages.dashboard.partials.role-dashboards.{$userRole}")
    </div>

    {{-- Toast de notification --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="refreshToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="icofont-check-circled me-2"></i>
                    Tableau de bord actualise
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@endsection

@push('plugins-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales/fr.js"></script>
@endpush

@push('js')
    <script src="{{ asset('app-js/dashboard/main.js') }}"></script>

    {{-- Variables globales pour le JavaScript --}}
    <script>
        window.dashboardRefreshUrl = "{{ route('opti-hr.dashboard.refresh') }}";
        window.dashboardCalendarUrl = "{{ route('opti-hr.dashboard.absence-calendar') }}";
    </script>
@endpush
