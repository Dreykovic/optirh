{{-- Widget: Cartes de statistiques avec tendances --}}
@foreach($statsCards as $stat)
<div class="col-md-6 col-lg-3">
    <div class="card dashboard-card stat-card h-100">
        <div class="card-body">
            <div class="stat-content">
                <h6 class="stat-label">{{ $stat['label'] }}</h6>
                <h2 class="stat-value" data-stat="{{ $stat['key'] }}">{{ number_format($stat['value']) }}</h2>

                @if(isset($stat['trend']) && $stat['trend'])
                <div class="stat-trend">
                    <span class="trend-indicator {{ $stat['trend']['direction'] === 'up' ? 'trend-up' : 'trend-down' }}">
                        <i class="icofont-arrow-{{ $stat['trend']['direction'] }}"></i>
                        {{ $stat['trend']['percentage'] }}%
                    </span>
                    <span class="trend-period">vs mois dernier</span>
                </div>
                @endif
            </div>
            <div class="stat-icon {{ $stat['iconBgClass'] }}">
                <i class="{{ $stat['icon'] }} {{ $stat['iconClass'] }}"></i>
            </div>
        </div>
    </div>
</div>
@endforeach
