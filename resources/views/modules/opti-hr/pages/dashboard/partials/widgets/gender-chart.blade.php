{{-- Widget: Graphique de repartition par genre --}}
<div class="card dashboard-card h-100">
    <div class="card-header">
        <h5 class="card-title">
            <i class="icofont-chart-pie-alt text-info"></i>
            Repartition par genre
        </h5>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="genderChart"></canvas>
        </div>
    </div>
</div>

@push('js')
<script>
    var genderChartData = @json($genderData['data'] ?? [0, 0]);
</script>
@endpush
