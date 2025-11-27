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
                                            in_array($absence->level, ['ONE', 'TWO', 'THREE']) &&
                                            auth()->user()->hasRole('DSAF')) ||
                                        (auth()->user()->employee_id !== $absence->duty->employee_id &&
                                            in_array($absence->level, ['TWO', 'THREE']) &&
                                            auth()->user()->hasRole('DG')) ||
                                        ($absence->stage !== 'CANCELLED' &&
                                            in_array($absence->level, ['ZERO', 'ONE', 'TWO', 'THREE']) &&
                                            auth()->user()->employee->duties->firstWhere('evolution', 'ON_GOING')->job_id ===
                                                $absence->duty->job->n_plus_one_job_id) ||
                                        ($absence->stage !== 'CANCELLED' &&
                                            in_array($absence->level, ['ZERO', 'ONE', 'TWO', 'THREE']) &&
                                            auth()->user()->hasRole('GRH') &&
                                            $absence->duty->job->n_plus_one_job_id === null))
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
                                        <td>
                                            @switch($absence->stage)
                                                @case('APPROVED')
                                                    <span class="status-badge status-approved">
                                                        <i class="bi bi-check-circle-fill me-1"></i>Approuvé
                                                    </span>
                                                @break

                                                @case('REJECTED')
                                                    <span class="status-badge status-rejected">
                                                        <i class="bi bi-x-circle-fill me-1"></i>Rejeté
                                                    </span>
                                                @break

                                                @case('CANCELLED')
                                                    <span class="status-badge status-cancelled">
                                                        <i class="bi bi-slash-circle me-1"></i>Annulé
                                                    </span>
                                                @break

                                                @case('IN_PROGRESS')
                                                    <span class="status-badge status-in-progress">
                                                        <i class="bi bi-hourglass-split me-1"></i>En traitement
                                                    </span>
                                                @break

                                                @case('COMPLETED')
                                                    <span class="status-badge status-completed">
                                                        <i class="bi bi-check-all me-1"></i>Complété
                                                    </span>
                                                @break

                                                @default
                                                    <span class="status-badge status-pending">
                                                        <i class="bi bi-clock me-1"></i>En attente
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @include('modules.opti-hr.pages.attendances.absences.actions')




                                        </td>
                                    </tr>
                                    @include('modules.opti-hr.pages.attendances.absences.request.comment')
                                    @include('modules.opti-hr.pages.attendances.absences.details')
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

                                            @case('HISTORY')
                                                <td colspan="7"> <x-no-data color="info" text="Aucune demande dans l'historique" /></td>
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
