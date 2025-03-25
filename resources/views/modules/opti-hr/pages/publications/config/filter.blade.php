<div id="filterOptions" class="card-body bg-light border-bottom p-3" style="display: none;">
    <div class="row g-2">
        <div class="col-12 col-md-3">
            <label for="statusFilter" class="form-label small">Statut</label>
            <select id="statusFilter" class="form-select form-select-sm">
                <option value="all">Tous</option>
                <option value="published">Publiés</option>
                <option value="pending">En attente</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <label for="dateFilter" class="form-label small">Date</label>
            <select id="dateFilter" class="form-select form-select-sm">
                <option value="all">Toutes dates</option>
                <option value="today">Aujourd'hui</option>
                <option value="week">Cette semaine</option>
                <option value="month">Ce mois</option>
            </select>
        </div>
        <div class="col-12 col-md-4">
            <label for="searchPublications" class="form-label small">Rechercher</label>
            <input type="search" id="searchPublications" class="form-control form-control-sm"
                placeholder="Rechercher par titre ou contenu">
        </div>
        <div class="col-12 col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-sm btn-primary w-100">Appliquer</button>
        </div>
    </div>
</div>
