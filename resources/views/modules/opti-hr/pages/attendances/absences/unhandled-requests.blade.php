<div class="row justify-content-center">
    <div class="col-lg-8 col-md-12">
        <div class="card mb-3">
            <div class="card-body d-sm-flex justify-content-between">
                <form class="" id="searchForm" data-model-url="{{ route('absences.requests', $stage) }}">
                    <div class=" d-flex">
                        <button type="submit" class="input-group-text" id="searchBtn"><i
                                class="icofont-ui-search"></i></button>
                        <input type="search" name="search" class="form-control" placeholder="Rechercher"
                            aria-label="Rechercher">
                    </div>
                </form>
                <div class="dropdown px-2">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Trier
                    </button>
                    <ul class="dropdown-menu  dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item"
                                href="{{ route('absences.requests', [$stage, 'type' => null]) }}">Tout</a>
                        </li>
                        @foreach ($absence_types as $absence_type)
                            <li><a class="dropdown-item"
                                    href="{{ route('absences.requests', [$stage, 'type' => $absence_type->id]) }}">{{ $absence_type->label }}</a>
                            </li>
                        @endforeach


                    </ul>

                </div>
            </div>

        </div>

        <div class="accordion" id="absenceRequestsList">




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
                    @include('modules.opti-hr.pages.attendances.absences.request.card')
                @endif


            @empty
                @switch($stage)
                    @case('IN_PROGRESS')
                        <div class="card mb-2"><x-no-data color="warning" text="Aucune Demande En Cours De Traitement" />
                        </div>
                    @break

                    @default
                        <div class="card mb-2"><x-no-data color="warning" text="Aucune Demande En Attente" />
                        </div>
                @endswitch
            @endforelse

        </div>

        {!! $absences->links() !!}
    </div>
</div> <!-- Row end  -->
