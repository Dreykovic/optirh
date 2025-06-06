<div type="div" class="btn   dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
    <span class="fw-bolder">...</span>
    <span class="visually-hidden">Toggle Dropdown</span>
</div>
<ul class="dropdown-menu border-0 shadow py-3 px-2">
    <li>
        <a class="dropdown-item py-2 rounded" data-bs-toggle="modal"
            data-bs-target="#documentReqDetails{{ $documentRequest->id }}" role="button">
            <i class="icofont-eye text-info"></i>

            <span class="d-none d-sm-none d-md-inline">Détails</span>
        </a>
        </div>
    </li>
    @if (
        ($documentRequest->level == 'ZERO' && auth()->user()->hasRole('GRH')) ||
            ($documentRequest->level == 'ONE' && auth()->user()->hasRole('DG')))
        <li>
            <div class="modelUpdateFormContainer dropdown-item py-2 rounded"
                id="documentRequestApproveForm{{ $documentRequest->id }}">

                <form data-model-update-url="{{ route('documents.approve', $documentRequest->id) }}">




                    <a role="button" class=" modelUpdateBtn " atl="update document request status">
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
            <div class="modelUpdateFormContainer dropdown-item py-2 rounded"
                id="documentRequestRejectForm{{ $documentRequest->id }}">

                <form data-model-update-url="{{ route('documents.reject', $documentRequest->id) }}">




                    <a role="button" class="modelUpdateBtn " atl="update document request status">
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
                data-bs-target="#documentRequestCommentAdd{{ $documentRequest->id }}" role="button">
                <i class="icofont-comment"></i>

                <span class="d-none d-sm-none d-md-inline">Commenter</span>
            </a>
            </div>
        </li>
    @endif

    @if (auth()->user()->employee_id === $documentRequest->duty->employee_id &&
            $documentRequest->level === 'ZERO' &&
            $documentRequest->stage !== 'CANCELLED')
        <li>
            <div class="modelUpdateFormContainer  dropdown-item py-2 rounded"
                id="documentRequestCancelForm{{ $documentRequest->id }}">

                <form data-model-update-url="{{ route('documents.cancel', $documentRequest->id) }}">




                    <a role="button" class="modelUpdateBtn " atl="update document request status">
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
    @if ($documentRequest->stage === 'APPROVED')
        <li>



            <a role="button" class="" atl="Download Pdf"
                href="{{ route('documents.download', $documentRequest->id) }}">

                <i class="icofont-download text-black"></i>
                <span class=" d-sm-none d-md-inline">Télécharger</span>

            </a>

        </li>
    @endif
</ul>
