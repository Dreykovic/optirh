@extends('modules.recours.pages.base')
@section('admin-content')
    <h2>Statistiques des Recours par Décision</h2>

    <!-- Formulaire de filtrage -->
    <form method="GET" action="{{ route('stats.index') }}" class="mb-3 row">
        <div class="col-md-4">
            <label for="start_date">De :</label>
            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>

        <div class="col-md-4">
            <label for="end_date">À :</label>
            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
        </div>
    </form>

    <!-- Affichage du graphe -->


    {!! $chart->container() !!}
@endsection

@push('plugins-js')
    <!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->
@endpush
@push('js')
    <!-- <script src="{{ asset('assets/js/page/hr.js') }}"></script> -->
    <!-- Charger le script de Laravel Charts -->
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endpush
