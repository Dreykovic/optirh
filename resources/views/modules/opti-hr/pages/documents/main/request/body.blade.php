<div class="card shadow-sm mb-4">
    <div class="card-body">
        <!-- En-tête avec titre et période -->
        <div class="row mb-4 border-bottom pb-3">
            <div class="col-md-7">
                <h5 class="fw-bold text-primary mb-1">
                    <i class="icofont-info-circle me-2"></i>Informations de la demande
                </h5>
                <p class="text-muted mb-0">Détails du document demandé</p>
            </div>
            <div class="col-md-5 text-md-end">
                <span class="badge bg-primary p-2 rounded-pill">
                    <i class="icofont-calendar me-1"></i>
                    <span class="fw-normal">Du</span> <strong>@formatDateOnly($documentRequest->start_date)</strong>
                    <span class="fw-normal">au</span> <strong>@formatDateOnly($documentRequest->end_date)</strong>
                </span>
            </div>
        </div>

        <!-- Informations principales -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm bg-light-primary rounded-circle me-2">
                        <i class="icofont-user-alt-4 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Supérieur Hiérarchique</h6>
                    </div>
                </div>
                <div class="ps-4 ms-2">
                    <p class="mb-0 fw-bold">
                        @if (
                            $documentRequest->duty->job->n_plus_one_job &&
                                $documentRequest->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING'))
                            {{ $documentRequest->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->last_name }}
                            {{ $documentRequest->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->first_name }}
                            <span
                                class="text-muted fs-6">({{ $documentRequest->duty->job->n_plus_one_job->title }})</span>
                        @else
                            <span class="text-muted">Néant</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm bg-light-primary rounded-circle me-2">
                        <i class="icofont-file-document text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Type de document</h6>
                    </div>
                </div>
                <div class="ps-4 ms-2">
                    <p class="mb-0 fw-bold">
                        {{ $documentRequest->document_type->label ?? 'Non spécifié' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Pièce jointe si disponible -->
        @if ($documentRequest->proof)
            <div class="card bg-light mb-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar lg bg-primary rounded-circle text-white me-3">
                            <i class="icofont-file-pdf fs-5"></i>
                        </div>


                    </div>
                </div>
            </div>
        @endif

        <!-- Commentaire -->
        <div class="card bg-light-secondary">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-2">
                    <i class="icofont-comment me-2"></i>Commentaire
                </h6>
                <p class="mb-0 ps-4">
                    @if ($documentRequest->comment)
                        {{ $documentRequest->comment }}
                    @else
                        <span class="text-muted fst-italic">Aucun commentaire n'a été ajouté à cette demande.</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
