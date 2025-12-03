{{-- Dashboard DG: Vue executive --}}

{{-- KPIs executifs --}}
@if(isset($kpis) && count($kpis) > 0)
<div class="row g-3 mb-4">
    @include('modules.opti-hr.pages.dashboard.partials.widgets.kpi-cards')
</div>
@endif

{{-- Statistiques executives --}}
@if(isset($statsCards) && count($statsCards) > 0)
<div class="row g-3 mb-4">
    @include('modules.opti-hr.pages.dashboard.partials.widgets.stat-cards')
</div>
@endif

{{-- File d'approbation et graphique --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.approval-queue')
    </div>
    <div class="col-lg-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.department-chart')
    </div>
</div>

{{-- Anniversaires et Publications --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.birthdays')
    </div>
    <div class="col-lg-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-publications')
    </div>
</div>

{{-- Absences recentes --}}
<div class="row g-3">
    <div class="col-12">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-absences')
    </div>
</div>
