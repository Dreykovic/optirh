<div class="card-body p-4">
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="absence-info-card bg-light p-3 rounded-3 h-100">
                <h6 class="border-bottom pb-2 mb-3">Informations de la demande</h6>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Période:</span>
                    <span class="fw-medium">
                        <i class="icofont-calendar me-1 text-primary"></i>
                        Du <strong>@formatDateOnly($absence->start_date)</strong>
                        au <strong>@formatDateOnly($absence->end_date)</strong>
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Jours demandés:</span>
                    <span class="fw-medium">
                        <i class="icofont-clock-time me-1 text-primary"></i>
                        <strong>{{ $absence->requested_days }}</strong> jours
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Solde disponible:</span>
                    <span class="fw-medium">
                        <i class="icofont-bank-alt me-1 text-primary"></i>
                        <strong>{{ $absence->duty->absence_balance }}</strong> jours
                    </span>
                </div>

                <div class="d-flex justify-content-between">
                    <span class="text-muted">Adresse durant l'absence:</span>
                    <span class="fw-medium text-truncate">
                        <i class="icofont-location-pin me-1 text-primary"></i>
                        {{ $absence->address ?: 'Non spécifiée' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <div class="absence-manager-card bg-light p-3 rounded-3 mb-3">
                <h6 class="border-bottom pb-2 mb-3">Supérieur hiérarchique</h6>
                <div class="d-flex align-items-center">
                    <i class="icofont-user-suited fs-5 me-2 text-primary"></i>
                    <span class="fw-medium">
                        {{ $absence->duty->job->n_plus_one_job
                            ? $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->last_name .
                                ' ' .
                                $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->first_name .
                                ' (' .
                                $absence->duty->job->n_plus_one_job->title .
                                ')'
                            : 'Néant' }}
                    </span>
                </div>
            </div>

            <div class="absence-reason-card bg-light p-3 rounded-3">
                <h6 class="border-bottom pb-2 mb-3">Motif de l'absence</h6>
                <p class="mb-0">{{ $absence->reasons ?: 'Aucun motif fourni' }}</p>
            </div>
        </div>
    </div>

    <!-- Proof Document Section -->
    @if ($absence->proof)
        <div class="mt-4 p-3 bg-light rounded-3">
            <h6 class="border-bottom pb-2 mb-3">Pièce justificative</h6>
            <div class="d-flex align-items-center">
                <span
                    class="avatar bg-primary rounded-circle text-white d-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px">
                    <i class="icofont-file-pdf"></i>
                </span>
                <div class="ms-3 flex-grow-1">
                    <h6 class="mb-0">file1.pdf</h6>
                    <span class="text-muted small">Justificatif d'absence</span>
                </div>
                <button type="button" class="btn btn-outline-primary">
                    <i class="icofont-download me-1"></i>Télécharger
                </button>
            </div>
        </div>
    @endif

    <!-- Comments Section -->
    <div class="mt-4 p-3 bg-light rounded-3">
        <h6 class="border-bottom pb-2 mb-3">Commentaire</h6>
        <div class="d-flex">
            <i class="icofont-comment fs-4 me-2 text-primary mt-1"></i>
            <p class="mb-0">{{ $absence->comment ?: 'Aucun commentaire' }}</p>
        </div>
    </div>
</div>
