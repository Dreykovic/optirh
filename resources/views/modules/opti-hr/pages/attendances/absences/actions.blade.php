<div type="div" class="btn   dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="fw-bolder">...</span>
    <span class="visually-hidden">Toggle Dropdown</span>
</div>
<ul class="dropdown-menu border-0 shadow py-3 px-2">
    <li>
        <a class="dropdown-item py-2 rounded" data-bs-toggle="modal" data-bs-target="#absenceReqDetails{{ $absence->id }}"
            role="button">
            <i class="icofont-eye text-info"></i>

            <span class="d-none d-sm-none d-md-inline">Détails</span>
        </a>
        </div>
    </li>
    @if (
        ($absence->level == 'ONE' && auth()->user()->hasRole('GRH')) ||
            ($absence->level == 'TWO' && auth()->user()->hasRole('DG')) ||
            ($absence->level == 'ZERO' &&
                auth()->user()->getCurrentDuty()?->job_id ===
                    $absence->duty->job->n_plus_one_job_id) ||
            (in_array($absence->level, ['ZERO']) &&
                auth()->user()->hasRole('GRH') &&
                $absence->duty->job->n_plus_one_job_id === null))
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
            <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
                data-bs-target="#absenceReject{{ $absence->id }}" role="button">
                <i class="icofont-close text-danger"></i>
                <span class="d-none d-sm-none d-md-inline">Rejeter</span>
            </a>
        </li>

        <li>
            <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
                data-bs-target="#absenceComment{{ $absence->id }}" role="button">
                <i class="icofont-comment text-primary"></i>
                <span class="d-none d-sm-none d-md-inline">Commenter</span>
            </a>
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
    @if ($absence->stage === 'APPROVED')
        <li>



            <a role="button" class="" atl="Download Pdf" href="{{ route('absences.download', $absence->id) }}">

                <i class="icofont-download text-black"></i>
                <span class=" d-sm-none d-md-inline">Télécharger</span>

            </a>

        </li>
    @endif
</ul>
