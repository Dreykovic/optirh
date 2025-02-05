<div class="accordion-header" id="absenceRequestLine{{ $absence->id }}">
    <div class="card">

        <div class="card-header d-sm-flex justify-content-between">
            <div href="javascript:void(0);" class="d-flex align-items-center">

                <x-employee-icon :employee="$employee" />

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
                    @switch($absence->stage)
                        @case('APPROVED')
                            <span class=" text-success">

                                Approuvé

                            </span>
                        @break

                        @case('REJECTED')
                            <span class=" text-danger">

                                Rejeté
                            </span>
                        @break

                        @case('CANCELLED')
                            <span class=" color-lavender-purple">

                                Annulé
                            </span>
                        @break

                        @case('IN_PROGRESS')
                            <span class=" text-warning">

                                En cours de Traitement
                            </span>
                        @break

                        @case('COMPLETED')
                            <span class=" ">

                                Complété
                            </span>
                        @break

                        @default
                            <span class="color-light-orange">

                                En attente
                            </span>
                    @endswitch

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
