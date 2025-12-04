{{-- Dashboard DSAF: Vue finances avec approbations --}}

{{-- Statistiques --}}
@if(isset($statsCards) && count($statsCards) > 0)
<div class="row g-3 mb-4">
    @include('modules.opti-hr.pages.dashboard.partials.widgets.stat-cards')
</div>
@endif

{{-- File d'approbation --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.approval-queue')
    </div>
    <div class="col-lg-4">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.birthdays')
    </div>
</div>

{{-- Absences recentes --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-absences')
    </div>
</div>

{{-- Publications --}}
<div class="row g-3">
    <div class="col-12">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-publications')
    </div>
</div>
