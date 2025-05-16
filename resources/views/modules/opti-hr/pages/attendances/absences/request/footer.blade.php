<div class="card-footer bg-white d-flex justify-content-between align-items-center p-3">
    <div class="d-none d-md-block">
        <span class="text-muted">Soumis le:</span>
        <span class="ms-2 fw-medium">@formatDate($absence->date_of_application)</span>
    </div>

    <div class="d-flex">
        @if (
            ($absence->level == 'ONE' && auth()->user()->hasRole('GRH')) ||
                ($absence->level == 'TWO' && auth()->user()->hasRole('DG')) ||
                ($absence->level == 'ZERO' &&
                    auth()->user()->employee->duties->firstWhere('evolution', 'ON_GOING')->job_id ===
                        $absence->duty->job->n_plus_one_job_id) ||
                (in_array($absence->level, ['ZERO']) &&
                    auth()->user()->hasRole('GRH') &&
                    $absence->duty->job->n_plus_one_job_id === null))
            <div class="modelUpdateFormContainer me-2" id="absenceApproveForm{{ $absence->id }}">
                <form data-model-update-url="{{ route('absences.approve', $absence->id) }}">
                    <button type="submit" class="btn btn-success lift modelUpdateBtn">
                        <span class="normal-status">
                            <i class="icofont-check me-1"></i>Approuver
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un instant...
                        </span>
                    </button>
                </form>
            </div>

            <div class="modelUpdateFormContainer me-2" id="absenceRejectForm{{ $absence->id }}">
                <form data-model-update-url="{{ route('absences.reject', $absence->id) }}">
                    <button type="submit" class="btn btn-danger lift modelUpdateBtn">
                        <span class="normal-status">
                            <i class="icofont-close me-1"></i>Rejeter
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un instant...
                        </span>
                    </button>
                </form>
            </div>

            <button class="btn btn-outline-primary me-2 lift" data-bs-toggle="modal"
                data-bs-target="#absenceCommentAdd{{ $absence->id }}">
                <i class="icofont-comment me-1"></i>Configurer
            </button>
        @endif

        @if (auth()->user()->employee_id === $absence->duty->employee_id && $absence->level === 'ZERO')
            <div class="modelUpdateFormContainer" id="absenceCancelForm{{ $absence->id }}">
                <form data-model-update-url="{{ route('absences.cancel', $absence->id) }}">
                    <button type="submit" class="btn btn-warning lift modelUpdateBtn">
                        <span class="normal-status">
                            <i class="icofont-ban me-1"></i>Annuler
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un instant...
                        </span>
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
