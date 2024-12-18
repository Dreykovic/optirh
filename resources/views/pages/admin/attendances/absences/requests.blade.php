@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <div class="row align-items-center">
        <div class="border-0 mb-4">
            <div
                class="card-header p-0 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                <h3 class="fw-bold py-3 mb-0">Demandes Absences</h3>
                <div class="d-flex py-2 project-tab flex-wrap w-sm-100">
                    <button type="button" class="btn btn-dark w-sm-100" data-bs-toggle="modal"
                        data-bs-target="#createAbsence"><i class="icofont-plus-circle me-2 fs-6"></i>Créer</button>
                    <ul class="nav nav-tabs tab-body-header rounded ms-3 prtab-set w-sm-100" role="tablist">
                        <li class="nav-item"><a class="nav-link {{ $stage === 'ALL' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'ALL') }}" role="tab">Toutes</a></li>

                        <li class="nav-item"><a
                                class="nav-link {{ $stage === 'PENDING' || $stage === 'PENDING' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'PENDING') }}" role="tab">Nouvelles</a></li>
                        <li class="nav-item"><a
                                class="nav-link {{ $stage === 'IN_PROGRESS' || $stage === 'IN_PROGRESS' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'IN_PROGRESS') }}" role="tab">En
                                Cours De Traitement</a></li>

                        <li class="nav-item"><a class="nav-link {{ $stage === 'APPROVED' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'APPROVED') }}" role="tab">Accordées</a></li>
                        <li class="nav-item"><a class="nav-link {{ $stage === 'REJECTED' ? 'active' : '' }}"
                                href="{{ route('absences.requests', 'REJECTED') }}" role="tab">Rejetées</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- Row end  -->

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
                                            {{ $absence->absence_type->label }}
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

                                            @case('IN_PROGRESS')
                                                <td colspan="7"> <x-no-data color="warning"
                                                        text="Aucune Demande En Cours De Traitement" />
                                                </td>
                                            @break

                                            @case('REJECTED')
                                                <td colspan="7"> <x-no-data color="warning" text="Aucune Demande Rejetée" /></td>
                                            @break

                                            @case('CANCELLED')
                                                <td colspan="7"> <x-no-data color="warning" text="Aucune Demande Annulée" /></td>
                                            @break

                                            @case('COMPLETED')
                                                <td colspan="7"> <x-no-data color="warning" text="Aucune Demande Complétée" />
                                                </td>
                                            @break

                                            @default
                                                <td colspan="7"> <x-no-data color="warning" text="Aucune Demande En Attente" />
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
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/attendances/absences/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
