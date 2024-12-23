<div type="div" class="btn   dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="fw-bolder">...</span>
    <span class="visually-hidden">Toggle Dropdown</span>
</div>
<ul class="dropdown-menu border-0 shadow py-3 px-2">

    @if (
        ($absence->level == 'ONE' && auth()->user()->hasRole('GRH')) ||
            ($absence->level == 'TWO' && auth()->user()->hasRole('DG')) ||
            ($absence->level == 'ZERO' &&
                auth()->user()->employee->duties->firstWhere('evolution', 'ON_GOING')->job_id ===
                    $absence->duty->job->n_plus_one_job_id))
        <li>
            <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
                data-bs-target="#absenceReqDetails{{ $absence->id }}" role="button">
                <i class="icofont-eye text-info"></i>

                <span class="d-none d-sm-none d-md-inline">Détails</span>
            </a>
            </div>
        </li>
        <li>
            <div class="modelUpdateFormContainer dropdown-item py-2 rounded" id="absenceApproveForm{{ $absence->id }}">

                <form data-model-update-url="{{ route('absences.approve', $absence->id) }}">




                    <a role="button" class=" modelUpdateBtn " atl="update client status">
                        <span class="normal-status">
                            <i class="icofont-check text-success  "></i>
                            <span class="d-none d-sm-none d-md-inline">Approuver</span>
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un Instant...
                        </span>
                    </a>

                </form>
            </div>
        </li>
        <li>
            <div class="modelUpdateFormContainer dropdown-item py-2 rounded" id="absenceRejectForm{{ $absence->id }}">

                <form data-model-update-url="{{ route('absences.reject', $absence->id) }}">




                    <a role="button" class="modelUpdateBtn " atl="update client status">
                        <span class="normal-status">
                            <i class="icofont-close text-danger"></i>
                            <span class="d-none d-sm-none d-md-inline">Rejeter</span>
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un Instant...
                        </span>
                    </a>

                </form>
            </div>
        </li>

        <li>
            <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
                data-bs-target="#absenceCommentAdd{{ $absence->id }}" role="button">
                <i class="icofont-comment"></i>

                <span class="d-none d-sm-none d-md-inline">Commenter</span>
            </a>
            </div>
        </li>
    @endif

    @if (auth()->user()->employee_id === $absence->duty->employee_id &&
            $absence->level === 'ZERO' &&
            $absence->stage !== 'CANCELLED')
        <li>
            <div class="modelUpdateFormContainer  dropdown-item py-2 rounded" id="absenceCancelForm{{ $absence->id }}">

                <form data-model-update-url="{{ route('absences.cancel', $absence->id) }}">




                    <a role="button" class="modelUpdateBtn " atl="update client status">
                        <span class="normal-status">
                            <i class="icofont-ban text-warning"></i>
                            <span class="d-none d-sm-none d-md-inline">Annuler</span>
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un Instant...
                        </span>
                    </a>

                </form>
            </div>
        </li>
    @endif
</ul>
