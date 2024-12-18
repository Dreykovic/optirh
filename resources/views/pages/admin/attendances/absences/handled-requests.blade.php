<div class="row clearfix g-3">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-body ">
                <div class="table-responsive">
                    <table id="absencesTable" class="table table-hover  align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Employée</th>
                                <th>Type Absence</th>
                                <th>De</th>
                                <th>A</th>
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
                                <tr>
                                    <td>
                                        <a href="#" class="fw-bold text-primary">{{ $employee->matricule }}</a>
                                    </td>
                                    <td>
                                        <img class="avatar rounded-circle"
                                            src={{ asset('assets/images/xs/avatar1.jpg') }} alt="">
                                        <span
                                            class="fw-bold ms-1">{{ $employee->last_name . ' ' . $employee->first_name }}</span>
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
                                    <td class="text-muted">
                                        <span class=" badge bg-warning">
                                            {{ $absence->stage }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-toggle="modal" data-bs-target="#leaveapprove"><i
                                                    class="icofont-check-circled text-success"></i></button>
                                            <button type="button" class="btn btn-outline-secondary deleterow"
                                                data-bs-toggle="modal" data-bs-target="#leavereject"><i
                                                    class="icofont-close-circled text-danger"></i></button>
                                        </div>
                                    </td>
                                </tr>

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
