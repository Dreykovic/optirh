{{-- Widget: Graphique de repartition par departement --}}
<div class="card dashboard-card h-100">
    <div class="card-header">
        <h5 class="card-title">
            <i class="icofont-chart-pie text-primary"></i>
            Repartition par departement
        </h5>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="departmentChart"></canvas>
        </div>
    </div>
</div>

@push('js')
<script>
    var departmentChartData = @json($departmentData ?? ['labels' => [], 'data' => []]);
</script>
@endpush
