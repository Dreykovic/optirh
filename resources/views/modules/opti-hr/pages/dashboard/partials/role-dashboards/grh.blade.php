{{-- Dashboard GRH: Acces complet aux fonctionnalites RH --}}

{{-- Actions rapides --}}
@include('modules.opti-hr.pages.dashboard.partials.widgets.quick-actions')

{{-- KPIs --}}
@if(isset($kpis) && count($kpis) > 0)
<div class="row g-3 mb-4">
    @include('modules.opti-hr.pages.dashboard.partials.widgets.kpi-cards')
</div>
@endif

{{-- Cartes de statistiques avec tendances --}}
@if(isset($statsCards) && count($statsCards) > 0)
<div class="row g-3 mb-4">
    @include('modules.opti-hr.pages.dashboard.partials.widgets.stat-cards')
</div>
@endif

{{-- Calendrier et Anniversaires --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.calendar')
    </div>
    <div class="col-lg-4">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.birthdays')
    </div>
</div>

{{-- Graphiques --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.department-chart')
    </div>
    <div class="col-md-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.gender-chart')
    </div>
</div>

{{-- Tableaux: Absences et Documents recents --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-absences')
    </div>
    <div class="col-lg-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-documents')
    </div>
</div>

{{-- Publications recentes --}}
<div class="row g-3">
    <div class="col-12">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-publications')
    </div>
</div>
