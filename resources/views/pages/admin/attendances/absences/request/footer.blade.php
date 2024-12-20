<div class="card-footer justify-content-between d-flex align-items-center">
    <div class="d-none d-md-block">
        <strong>Soumis Le :</strong>
        <span>@formatDate($absence->date_of_application)</span>
    </div>
    <div class="card-hover-show d-flex">
        <div class="modelUpdateFormContainer" id="absenceApproveForm{{ $absence->id }}">

            <form data-model-update-url="{{ route('absences.approve', $absence->id) }}">




                <button type="submit" class="btn btn-outline-primary btn-sm border lift modelUpdateBtn "
                    atl="update client status">
                    <span class="normal-status">
                        <i class="icofont-check-circled text-success"></i>
                        <span class="d-none d-sm-none d-md-inline">Approuver</span>
                    </span>
                    <span class="indicateur d-none">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Un Instant...
                    </span>
                </button>

            </form>
        </div>

        <div class="modelUpdateFormContainer" id="absenceRejectForm{{ $absence->id }}">

            <form data-model-update-url="{{ route('absences.reject', $absence->id) }}">




                <button type="submit" class="btn btn-outline-primary btn-sm border lift modelUpdateBtn "
                    atl="update client status">
                    <span class="normal-status">
                        <i class="icofont-close-circled text-danger"></i>
                        <span class="d-none d-sm-none d-md-inline">Rejeter</span>
                    </span>
                    <span class="indicateur d-none">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Un Instant...
                    </span>
                </button>

            </form>
        </div>



        <button class="btn btn-outline-primary btn-sm border lift" data-bs-toggle="modal"
            data-bs-target="#absenceCommentAdd{{ $absence->id }}"><i class="bi bi-chat-left-text-fill"></i>
            <span class="d-none d-sm-none d-md-inline">Commenter</span></button>
    </div>
</div>
