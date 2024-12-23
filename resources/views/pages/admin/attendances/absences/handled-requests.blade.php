<div class="row clearfix g-3">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-body ">
                <div class="table-responsive">
                    <table id="absencesTable" class="table table-hover  align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Employée</th>
                                <th>Type Absence</th>
                                <th>De</th>
                                <th>A</th>
                                <th>Nbr Jrs</th>

                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($absences as $absence)
                                @php
                                    $employee = $absence->duty->employee;
                                    $absence_type = $absence->absence_type;
                                @endphp
                                @if (auth()->user()->employee_id === $absence->duty->employee_id ||
                                        (auth()->user()->employee_id !== $absence->duty->employee_id &&
                                            in_array($absence->level, ['ONE', 'TWO', 'THREE']) &&
                                            auth()->user()->hasRole('GRH')) ||
                                        (auth()->user()->employee_id !== $absence->duty->employee_id &&
                                            in_array($absence->level, ['TWO', 'THREE']) &&
                                            auth()->user()->hasRole('DG')) ||
                                        ($absence->stage !== 'CANCELLED' &&
                                            in_array($absence->level, ['ZERO', 'ONE', 'TWO', 'THREE']) &&
                                            auth()->user()->employee->duties->firstWhere('evolution', 'ON_GOING')->job_id ===
                                                $absence->duty->job->n_plus_one_job_id))
                                    <tr>

                                        <td>

                                            <x-employee-icon :employee="$employee" />
                                            <a href="#" class="fw-bold">
                                                <span>{{ $employee->last_name . ' ' . $employee->first_name }}
                                                </span>
                                            </a>




                                        </td>
                                        <td>
                                            {{ !$absence_type ? 'Pas Définis' : $absence_type->label }}
                                        </td>
                                        <td>
                                            @formatDateOnly($absence->start_date)

                                        </td>
                                        <td>
                                            @formatDateOnly($absence->end_date)
                                        </td>
                                        <td>
                                            {{ $absence->requested_days }} Jours
                                        </td>
                                        <td class="fw-bolder text-uppercase">
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

                                        </td>
                                        <td>
                                            @include('pages.admin.attendances.absences.actions')




                                        </td>
                                    </tr>
                                    @include('pages.admin.attendances.absences.request.comment')
                                    @include('pages.admin.attendances.absences.details')
                                @endif

                                @empty
                                    <tr>
                                        @switch($stage)
                                            @case('APPROVED')
                                                <td colspan="7"> <x-no-data color="warning" text="Aucune Demande Approuvée" />
                                                </td>
                                            @break

                                            @case('REJECTED')
                                                <td colspan="7"> <x-no-data color="warning" text="Aucune Demande Rejetée" /></td>
                                            @break

                                            @case('CANCELLED')
                                                <td colspan="7"> <x-no-data color="warning" text="Aucune Demande Annulée" /></td>
                                            @break

                                            @default
                                                <td colspan="7"> <x-no-data color="warning" text="Aucune Demande Complétée" />
                                                </td>
                                        @endswitch


                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Row End -->
