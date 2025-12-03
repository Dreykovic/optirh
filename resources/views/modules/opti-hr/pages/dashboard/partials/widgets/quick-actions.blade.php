{{-- Widget: Actions rapides (GRH uniquement) --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card dashboard-card quick-actions-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="icofont-flash text-primary"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3 col-6">
                        <a href="{{ route('membres.pages') }}" class="btn btn-primary quick-action-btn">
                            <i class="icofont-user-alt-3"></i>
                            <span>Ajouter un employe</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('directions') }}" class="btn btn-secondary quick-action-btn">
                            <i class="icofont-building"></i>
                            <span>Ajouter un departement</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('absences.create') }}" class="btn btn-info quick-action-btn text-white">
                            <i class="icofont-calendar"></i>
                            <span>Enregistrer une absence</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('publications.config.index') }}" class="btn btn-success quick-action-btn">
                            <i class="icofont-file-document"></i>
                            <span>Nouvelle publication</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
