<div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-primary bg-gradient text-white py-3">
        <h5 class="mb-0 d-flex align-items-center">
            <i class="icofont-ui-calendar me-2"></i>Détails de la demande d'absence
        </h5>
    </div>

    <div class="card-body p-0">
        <!-- Statut de la demande - nouveau bloc -->
        <div class="p-3 d-flex justify-content-between align-items-center bg-light border-bottom">
            <div class="d-flex align-items-center">
                <div class="status-indicator me-3">
                    @switch($absence->stage)
                        @case('PENDING')
                            <span class="badge bg-warning px-3 py-2">
                                <i class="icofont-hour-glass me-1"></i>En attente
                            </span>
                            @break
                        @case('IN_PROGRESS')
                            <span class="badge bg-info px-3 py-2">
                                <i class="icofont-running-horse me-1"></i>En cours
                            </span>
                            @break
                        @case('APPROVED')
                            <span class="badge bg-success px-3 py-2">
                                <i class="icofont-check-circled me-1"></i>Approuvée
                            </span>
                            @break
                        @case('REJECTED')
                            <span class="badge bg-danger px-3 py-2">
                                <i class="icofont-close-circled me-1"></i>Rejetée
                            </span>
                            @break
                        @case('CANCELLED')
                            <span class="badge bg-secondary px-3 py-2">
                                <i class="icofont-ban me-1"></i>Annulée
                            </span>
                            @break
                        @default
                            <span class="badge bg-secondary px-3 py-2">
                                <i class="icofont-info-circle me-1"></i>{{ $absence->stage }}
                            </span>
                    @endswitch
                </div>
                <div>
                    <div class="text-muted small">Déposée le</div>
                    <div class="fw-bold">@formatDateOnly($absence->date_of_application)</div>
                </div>
            </div>
            
            <!-- Indicateur de déductibilité -->
            <div class="deductibility-indicator px-3">
                @if($absence->absence_type->is_deductible)
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill bg-warning bg-opacity-25 text-black border border-warning px-3 py-2 me-2">
                            <i class="icofont-minus-circle me-1"></i>Déductible
                        </span>
                        <span class="text-muted small">Impact sur le solde</span>
                    </div>
                @else
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill bg-success bg-opacity-25 text-black border border-success px-3 py-2 me-2">
                            <i class="icofont-plus-circle me-1"></i>Non déductible
                        </span>
                        <span class="text-muted small">Pas d'impact sur le solde</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Type d'absence - nouveau bloc -->
        <div class="p-3 bg-primary bg-opacity-10 border-bottom">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary rounded-circle p-2 me-3">
                    <i class="icofont-listing-box"></i>
                </div>
                <div>
                    <div class=" small">Type d'absence</div>
                    <div class="fw-bold fs-5">{{ $absence->absence_type->label }}</div>
                </div>
            </div>
        </div>

        <!-- Résumé principal - bloc repensé -->
        <div class="py-4 px-4 border-bottom">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="icofont-calendar text-white"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Période</div>
                            <div class="fw-bold">@formatDateOnly($absence->start_date) - @formatDateOnly($absence->end_date)</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="icofont-clock-time text-white"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Jours demandés</div>
                            <div class="fw-bold">{{ $absence->requested_days }} jour{{ $absence->requested_days > 1 ? 's' : '' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="icofont-bank-alt text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Solde disponible</div>
                            <div class="fw-bold text-success">{{ $absence->duty->absence_balance }} jour{{ $absence->duty->absence_balance > 1 ? 's' : '' }}</div>
                            @if($absence->absence_type->is_deductible && $absence->stage === 'APPROVED')
                                <div class="text-muted small mt-1">
                                    <span class="text-warning">
                                        <i class="icofont-minus-circle"></i>
                                        {{ $absence->requested_days }} jour{{ $absence->requested_days > 1 ? 's' : '' }} déduit{{ $absence->requested_days > 1 ? 's' : '' }}
                                    </span>
                                </div>
                            @endif
                        </div>
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
                        <div class="bg-light rounded-3 p-3 border">
                            <p class="mb-0">{{ $absence->reasons ?: 'Aucun motif fourni' }}</p>
                        </div>
                    </div>

                    <!-- Section adresse -->
                    <div class="p-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="icofont-location-pin me-2 text-primary"></i>Adresse durant l'absence
                        </h6>
                        <div class="bg-light rounded-3 p-3 border">
                            <p class="mb-0">{{ $absence->address ?: 'Non spécifiée' }}</p>
                        </div>
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
                        <div class="d-flex align-items-center bg-light rounded-3 p-3 border">
                            <div class="avatar bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px">
                                <i class="icofont-user"></i>
                            </div>
                            <div>
                                <div class="fw-bold">
                                    {{ $absence->duty->job->n_plus_one_job
                                        ? $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->last_name .
                                            ' ' .
                                            $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->first_name
                                        : 'Néant' }}
                                </div>
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
                        <div class="bg-light rounded-3 p-3 border">
                            <p class="mb-0">{{ $absence->comment ?: 'Aucun commentaire' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       
        
     
    </div>
</div>