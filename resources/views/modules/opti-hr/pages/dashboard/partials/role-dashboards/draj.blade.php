{{-- Dashboard DRAJ: Acces au module Recours --}}

{{-- Lien vers le module Recours --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-body text-center py-5">
                <i class="icofont-law-document text-primary" style="font-size: 4rem;"></i>
                <h3 class="mt-3 mb-2">Gestion des Recours</h3>
                <p class="text-muted mb-4">
                    Acces au module de gestion des recours administratifs
                </p>
                <a href="{{ route('recours.home') }}" class="btn btn-primary btn-lg">
                    <i class="icofont-arrow-right me-2"></i>
                    Acceder au module Recours
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Statistiques basiques --}}
@if(isset($statsCards) && count($statsCards) > 0)
<div class="row g-3 mb-4">
    @foreach(array_slice($statsCards, 0, 2) as $stat)
    <div class="col-md-6">
        <div class="card dashboard-card stat-card h-100">
            <div class="card-body">
                <div class="stat-content">
                    <h6 class="stat-label">{{ $stat['label'] }}</h6>
                    <h2 class="stat-value">{{ number_format($stat['value']) }}</h2>
                </div>
                <div class="stat-icon {{ $stat['iconBgClass'] }}">
                    <i class="{{ $stat['icon'] }} {{ $stat['iconClass'] }}"></i>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Anniversaires et Publications --}}
<div class="row g-3">
    <div class="col-lg-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.birthdays')
    </div>
    <div class="col-lg-6">
        @include('modules.opti-hr.pages.dashboard.partials.widgets.recent-publications')
    </div>
</div>
