<div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-primary bg-gradient text-white py-3">
        <h5 class="mb-0 d-flex align-items-center">
            <i class="icofont-ui-calendar me-2"></i>Détails de la demande d'absence
        </h5>
    </div>

    <div class="card-body p-0">
        <!-- Résumé principal - bloc distinctif -->
        <div class="bg-light py-3 px-4 border-bottom">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                        <i class="icofont-calendar text-light"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Période</div>
                        <div class="fw-bold">@formatDateOnly($absence->start_date) - @formatDateOnly($absence->end_date)</div>
                    </div>
                </div>

                <div class="d-flex align-items-center ms-md-4">
                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                        <i class="icofont-clock-time text-light"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Jours demandés</div>
                        <div class="fw-bold">{{ $absence->requested_days }} jours</div>
                    </div>
                </div>

                <div class="d-flex align-items-center ms-md-4">
                    <div class="icon-box bg-success bg-opacity-10 rounded-circle p-2 me-2">
                        <i class="icofont-bank-alt text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Solde disponible</div>
                        <div class="fw-bold text-success">{{ $absence->duty->absence_balance }} jours</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-0">
            <!-- Colonne gauche -->
            <div class="col-md-6 border-end">
                <div class="h-100">
                    <!-- Section motif d'absence -->
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="icofont-info-circle me-2 text-primary"></i>Motif de l'absence
                        </h6>
                        <div class="bg-light rounded-3 p-3">
                            <p class="mb-0">{{ $absence->reasons ?: 'Aucun motif fourni' }}</p>
                        </div>
                    </div>

                    <!-- Section adresse -->
                    <div class="p-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="icofont-location-pin me-2 text-primary"></i>Adresse durant l'absence
                        </h6>
                        <p class="mb-0">{{ $absence->address ?: 'Non spécifiée' }}</p>
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="col-md-6">
                <div class="h-100">
                    <!-- Section supérieur hiérarchique -->
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="icofont-user-suited me-2 text-primary"></i>Supérieur hiérarchique
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 text-light me-3">
                                <i class="icofont-user"></i>
                            </div>
                            <div>
                                {{ $absence->duty->job->n_plus_one_job
                                    ? $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->last_name .
                                        ' ' .
                                        $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->first_name
                                    : 'Néant' }}
                                <div class="text-muted small">
                                    {{ $absence->duty->job->n_plus_one_job ? $absence->duty->job->n_plus_one_job->title : '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section commentaire -->
                    <div class="p-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="icofont-comment me-2 text-primary"></i>Commentaire
                        </h6>
                        <p class="mb-0">{{ $absence->comment ?: 'Aucun commentaire' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pièce justificative si disponible -->
        @if ($absence->proof)
            <div class="border-top p-4">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="icofont-attachment me-2 text-primary"></i>Pièce justificative
                </h6>
                <div class="d-flex align-items-center bg-light p-3 rounded-3">
                    <i class="icofont-file-pdf fs-3 text-danger me-3"></i>
                    <div class="flex-grow-1">
                        <div class="fw-medium">file1.pdf</div>
                        <div class="text-muted small">Justificatif d'absence</div>
                    </div>
                    <a href="#" class="text-decoration-none text-primary">
                        <i class="icofont-download me-1"></i>Télécharger
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
