<form id="filterOptions" class="card-body bg-light border-bottom p-3" method="GET" action="{{ route('publications.config.index', $status ?? 'all') }}">
    <div class="row g-2">
        <div class="col-12 col-md-3">
            <label for="dateFilter" class="form-label small">Date</label>
            <select id="dateFilter" name="date_filter" class="form-select form-select-sm">
                <option value="all" {{ ($filters['date_filter'] ?? 'all') === 'all' ? 'selected' : '' }}>Toutes dates</option>
                <option value="today" {{ ($filters['date_filter'] ?? '') === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                <option value="week" {{ ($filters['date_filter'] ?? '') === 'week' ? 'selected' : '' }}>Cette semaine</option>
                <option value="month" {{ ($filters['date_filter'] ?? '') === 'month' ? 'selected' : '' }}>Ce mois</option>
            </select>
        </div>
        <div class="col-12 col-md-5">
            <label for="searchPublications" class="form-label small">Rechercher</label>
            <input type="search" id="searchPublications" name="search" class="form-control form-control-sm"
                placeholder="Rechercher par titre ou contenu" value="{{ $filters['search'] ?? '' }}">
        </div>
        <div class="col-12 col-md-4 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                <i class="icofont-search-1 me-1"></i> Appliquer
            </button>
            @if(($filters['date_filter'] ?? 'all') !== 'all' || !empty($filters['search'] ?? ''))
                <a href="{{ route('publications.config.index', $status ?? 'all') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="icofont-refresh"></i> Réinitialiser
                </a>
            @endif
        </div>
    </div>
</form>
