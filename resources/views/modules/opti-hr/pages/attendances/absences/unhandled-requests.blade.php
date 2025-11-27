<div class="row justify-content-center">
    <div class="col-lg-8 col-md-12">
        {{-- Sous-filtres rapides --}}
        @if(isset($subFilterCounts))
        <div class="quick-filters-container mb-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="filter-label text-muted small me-2">
                    <i class="bi bi-funnel me-1"></i>Filtrer :
                </span>
                <a href="{{ route('absences.requests', [$stage, 'filter' => 'all']) }}"
                   class="btn btn-sm {{ (!isset($subFilter) || $subFilter === 'all') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Toutes
                    <span class="badge {{ (!isset($subFilter) || $subFilter === 'all') ? 'bg-light text-primary' : 'bg-secondary' }} ms-1">
                        {{ $subFilterCounts['all'] ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('absences.requests', [$stage, 'filter' => 'mine']) }}"
                   class="btn btn-sm {{ (isset($subFilter) && $subFilter === 'mine') ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="bi bi-person me-1"></i>Mes demandes
                    <span class="badge {{ (isset($subFilter) && $subFilter === 'mine') ? 'bg-light text-primary' : 'bg-secondary' }} ms-1">
                        {{ $subFilterCounts['mine'] ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('absences.requests', [$stage, 'filter' => 'to_validate']) }}"
                   class="btn btn-sm {{ (isset($subFilter) && $subFilter === 'to_validate') ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                    <i class="bi bi-check2-square me-1"></i>À valider
                    <span class="badge {{ (isset($subFilter) && $subFilter === 'to_validate') ? 'bg-dark' : 'bg-warning text-dark' }} ms-1">
                        {{ $subFilterCounts['to_validate'] ?? 0 }}
                    </span>
                </a>
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-body d-sm-flex justify-content-between">
                <form class="" id="searchForm" data-model-url="{{ route('absences.requests', $stage) }}">
                    <input type="hidden" name="filter" value="{{ $subFilter ?? 'all' }}">
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
                        Trier par type
                    </button>
                    <ul class="dropdown-menu  dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item"
                                href="{{ route('absences.requests', [$stage, 'type' => null, 'filter' => $subFilter ?? 'all']) }}">Tous les types</a>
                        </li>
                        @foreach ($absence_types as $absence_type)
                            <li><a class="dropdown-item"
                                    href="{{ route('absences.requests', [$stage, 'type' => $absence_type->id, 'filter' => $subFilter ?? 'all']) }}">{{ $absence_type->label }}</a>
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

                    @case('TO_PROCESS')
                        <div class="card mb-2"><x-no-data color="info" text="Aucune demande à traiter" />
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
