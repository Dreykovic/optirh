<div class="card-footer justify-content-between d-flex align-items-center">
    <div class="d-none d-md-block">
        <strong>Soumis Le :</strong>
        <span>@formatDate($absence->date_of_application)</span>
    </div>
    <div class="card-hover-show d-flex ">
        @if (auth()->user()->can('écrire-une-absence'))

            @if (
                ($absence->level == 'TWO' && auth()->user()->hasRole('GRH')) ||
                    ($absence->level == 'THREE' && auth()->user()->hasRole('DG')))
                <div class="modelUpdateFormContainer mx-2" id="absenceApproveForm{{ $absence->id }}">

                    <form data-model-update-url="{{ route('absences.approve', $absence->id) }}">




                        <button type="submit" class="btn btn-outline-primary btn-sm  lift modelUpdateBtn "
                            atl="update client status">
                            <span class="normal-status">
                                <i class="icofont-check text-success"></i>
                                <span class="d-none d-sm-none d-md-inline">Approuver</span>
                            </span>
                            <span class="indicateur d-none">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Un Instant...
                            </span>
                        </button>

                    </form>
                </div>
            @endif

            <div class="modelUpdateFormContainer  mx-2" id="absenceRejectForm{{ $absence->id }}">

                <form data-model-update-url="{{ route('absences.reject', $absence->id) }}">




                    <button type="submit" class="btn btn-outline-danger btn-sm  lift modelUpdateBtn "
                        atl="update client status">
                        <span class="normal-status">
                            <i class="icofont-close text-danger"></i>
                            <span class="d-none d-sm-none d-md-inline">Rejeter</span>
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un Instant...
                        </span>
                    </button>

                </form>
            </div>




            <button class="btn btn-outline-dark btn-sm  lift  mx-2" data-bs-toggle="modal"
                data-bs-target="#absenceCommentAdd{{ $absence->id }}"> <i class="icofont-comment"></i>

                <span class="d-none d-sm-none d-md-inline">Commenter</span></button>
        @endif
        @if (auth()->user()->employee_id === $absence->duty->employee_id)
            <div class="modelUpdateFormContainer  mx-2" id="absenceCancelForm{{ $absence->id }}">

                <form data-model-update-url="{{ route('absences.cancel', $absence->id) }}">




                    <button type="submit" class="btn btn-outline-warning btn-sm   lift modelUpdateBtn "
                        atl="update client status">
                        <span class="normal-status">
                            <i class="icofont-ban text-warning"></i>
                            <span class="d-none d-sm-none d-md-inline">Annuler</span>
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un Instant...
                        </span>
                    </button>

                </form>
            </div>
        @endif

    </div>
</div>
