{{-- Widget: Cartes KPI --}}
@foreach($kpis as $kpi)
<div class="col-md-4">
    <div class="card dashboard-card kpi-card kpi-{{ $kpi['color'] ?? 'primary' }} h-100">
        <div class="card-body">
            <div class="kpi-value">
                {{ $kpi['value'] }}<span class="kpi-unit">{{ $kpi['unit'] }}</span>
            </div>
            <div class="kpi-label">{{ $kpi['label'] }}</div>
        </div>
    </div>
</div>
@endforeach
