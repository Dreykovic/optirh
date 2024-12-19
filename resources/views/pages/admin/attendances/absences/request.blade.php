<div class="accordion-item  mb-3">
    <div class="accordion-header" id="absenceRequestLine{{ $absence->id }}">
        <div class="card">

            <div class="card-header d-sm-flex justify-content-between">
                <div href="javascript:void(0);" class="d-flex align-items-center">
                    <img class="avatar rounded-circle" src={{ asset('assets/images/xs/avatar1.jpg') }} alt="">
                    <div class="flex-fill ms-3 text-truncate">
                        <a href="#">
                            <h6 class="d-flex justify-content-between mb-0">
                                <span>{{ $employee->last_name . ' ' . $employee->first_name }}</span>
                            </h6>
                        </a>
                        <span class="text-muted ">{{ $absence->duty->job->title }}</span>

                        <p class="">{{ $absence->duty->job->department->name }}</p>
                    </div>
                </div>
                <div class="text-end d-none d-md-block">
                    <p class="mb-1">
                    <h6 class="">{{ !$absence_type ? 'Pas Définis' : $absence_type->label }}
                    </h6>
                    </p>
                    <span class="float-end "> <strong>Status:</strong>
                        <span class="text-warning"> {{ $absence->stage }}

                        </span>
                    </span>
                </div>
            </div>

        </div>
        <button class="accordion-button " type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseAbsenceRequestLine{{ $absence->id }}" aria-expanded="true"
            aria-controls="collapseAbsenceRequestLine{{ $absence->id }}">
            Voir les Détails
        </button>
    </div>
    <div id="collapseAbsenceRequestLine{{ $absence->id }}" class="accordion-collapse collapse "
        aria-labelledby="absenceRequestLine{{ $absence->id }}" data-bs-parent="#absenceRequestsList">
        <div class="accordion-body">
            <div class="card">


                <div class="card-body ">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="mb-3">Supérieur Hiérarchique:</h6>
                            <div>Période</div>
                            <div>Jours </div>
                            <div>Solde</div>
                            <div>Adresse De Congé</div>

                        </div>

                        <div class="col-sm-6">
                            <h6 class="mb-3"> {{ $absence->duty->job->title }} </h6>
                            <div> Du <strong> @formatDateOnly($absence->start_date)</strong> Au
                                <strong> @formatDateOnly($absence->end_date)
                                </strong>
                            </div>
                            <div>
                                <strong>{{ $absence->requested_days }}</strong> Jrs
                            </div>
                            <div> <strong>{{ $absence->duty->absence_balance }}</strong> Jrs </div>
                            <div>{{ $absence->address }}</div>

                        </div>
                    </div> <!-- Row end  -->
                    <div class="">
                        <span class="float-start"> <strong>Raison : </strong> </span>
                        <p class="ml-1">{{ ' ' . $absence->reasons }}</p>

                    </div>
                    <div class="mb-3">
                        <div class="py-2 d-flex align-items-center border-bottom">
                            <div class="d-flex ms-3 align-items-center flex-fill">
                                <span
                                    class="avatar lg bg-lightgreen rounded-circle text-center d-flex align-items-center justify-content-center"><i
                                        class="icofont-file-pdf fs-5"></i></span>
                                <div class="d-flex flex-column ps-3">
                                    <h6 class="fw-bold mb-0 small-14">file1.pdf</h6>
                                </div>
                            </div>
                            <button type="button" class="btn bg-lightgreen text-end">Download</button>
                        </div>

                    </div>
                    <div class="mb-3 pb-3 border-bottom">

                        <span class="float-start"> <strong>Commentaire : </strong>
                        </span>
                        <p class="ml-1">{{ $absence->comment ? ' ' . $absence->comment : ' Aucun' }}</p>
                    </div>



                </div>

            </div>
        </div>
    </div>
    <div class="card">



        <div class="card-footer justify-content-between d-flex align-items-center">
            <div class="d-none d-md-block">
                <strong>Soumis Le :</strong>
                <span>@formatDate($absence->date_of_application)</span>
            </div>
            <div class="card-hover-show">
                <a class=" btn btn-outline-primary btn-sm border lift" href="#"><i
                        class="icofont-check-circled text-success"></i> <span
                        class="d-none d-sm-none d-md-inline">Approuver</span></a>

                <a class=" btn btn-outline-primary btn-sm border lift" href="#"><i
                        class="icofont-close-circled text-danger"></i> <span
                        class="d-none d-sm-none d-md-inline">Rejeter</span></a>
                <a class="btn btn-outline-primary btn-sm border lift" href="#"><i
                        class="bi bi-chat-left-text-fill"></i>
                    <span class="d-none d-sm-none d-md-inline">Annuler</span></a>
            </div>

        </div>
    </div>
</div>
