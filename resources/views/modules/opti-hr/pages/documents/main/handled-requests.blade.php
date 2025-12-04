<div class="row clearfix g-3">
    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-body ">
                <div class="table-responsive">
                    <table id="documentRequestsTable" class="table table-hover  align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Employée</th>
                                <th>Type De Document</th>
                                <th>De</th>
                                <th>A</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documentRequests as $documentRequest)
                                @php
                                    $employee = $documentRequest->duty->employee;
                                    $document_type = $documentRequest->document_type;
                                @endphp
                                @if (auth()->user()->employee_id === $documentRequest->duty->employee_id ||
                                        ($documentRequest->stage !== 'CANCELLED' &&
                                            auth()->user()->employee_id !== $documentRequest->duty->employee_id &&
                                            in_array($documentRequest->level, ['ZERO', 'ONE', 'TWO', 'THREE']) &&
                                            auth()->user()->hasRole('GRH')) ||
                                        ($documentRequest->stage !== 'CANCELLED' &&
                                            auth()->user()->employee_id !== $documentRequest->duty->employee_id &&
                                            in_array($documentRequest->level, ['ONE', 'TWO', 'THREE']) &&
                                            auth()->user()->hasRole('DSAF')) ||
                                        (auth()->user()->employee_id !== $documentRequest->duty->employee_id &&
                                            in_array($documentRequest->level, ['ONE', 'TWO', 'THREE']) &&
                                            auth()->user()->hasRole('DG')))
                                    <tr>

                                        <td>

                                            <x-employee-icon :employee="$employee" />
                                            <a href="#" class="fw-bold">
                                                <span>{{ $employee->last_name . ' ' . $employee->first_name }}
                                                </span>
                                            </a>




                                        </td>
                                        <td>
                                            {{ !$document_type ? 'Pas Définis' : $document_type->label }}
                                        </td>
                                        <td>
                                            @formatDateOnly($documentRequest->start_date)

                                        </td>
                                        <td>
                                            @formatDateOnly($documentRequest->end_date)
                                        </td>

                                        <td class="fw-bolder text-uppercase">
                                            @switch($documentRequest->stage)
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
                                                    <span class="text-secondary">
                                                        <i class="icofont-ban me-1"></i>Annulé
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
                                                    <span class="text-warning">
                                                        <i class="icofont-clock-time me-1"></i>En attente
                                                    </span>
                                            @endswitch

                                        </td>
                                        <td>
                                            @include('modules.opti-hr.pages.documents.main.actions')




                                        </td>
                                    </tr>
                                    @include('modules.opti-hr.pages.documents.main.request.comment')
                                    @include('modules.opti-hr.pages.documents.main.details')
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
