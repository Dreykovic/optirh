<div class="row clearfix g-3">

    <div class="col-sm-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="border-0 mb-4">
                    <div
                        class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                        <h3 class="fw-bold mb-0">Demandes</h3>
                        <div class="col-auto d-flex">
                            <form class="" id="searchForm"
                                data-model-url="{{ route('absences.requests', $stage) }}">
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
                </div>
                <div class="row clearfix g-3">
                    <div class="col-lg-4 col-md-12">
                        <div class="feedback-info sticky-top">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class=" display-6 fw-bold mb-0">4.5</h2>
                                    <small class="text-muted">based on 1,032 ratings</small>
                                    <div class="d-flex align-items-center">
                                        <span class="mb-2 me-3">
                                            <a href="#" class="rating-link active"><i
                                                    class="bi bi-star-fill text-warning"></i></a>
                                            <a href="#" class="rating-link active"><i
                                                    class="bi bi-star-fill text-warning"></i></a>
                                            <a href="#" class="rating-link active"><i
                                                    class="bi bi-star-fill text-warning"></i></a>
                                            <a href="#" class="rating-link active"><i
                                                    class="bi bi-star-fill text-warning"></i></a>
                                            <a href="#" class="rating-link active"><i
                                                    class="bi bi-star-half text-warning"></i></a>
                                        </span>
                                    </div>
                                    <div class="progress-count mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold d-flex align-items-center">5<i
                                                    class="bi bi-star-fill text-warning ms-1 small-11 pb-1"></i></h6>
                                            <span class="small text-muted">661</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar light-success-bg" role="progressbar"
                                                style="width: 92%" aria-valuenow="92" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="progress-count mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold d-flex align-items-center">4<i
                                                    class="bi bi-star-fill text-warning ms-1 small-11 pb-1"></i></h6>
                                            <span class="small text-muted">237</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-info-light" role="progressbar"
                                                style="width: 60%" aria-valuenow="60" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="progress-count mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold d-flex align-items-center">3<i
                                                    class="bi bi-star-fill text-warning ms-1 small-11 pb-1"></i></h6>
                                            <span class="small text-muted">76</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-lightyellow" role="progressbar"
                                                style="width: 40%" aria-valuenow="40" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="progress-count mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold d-flex align-items-center">2<i
                                                    class="bi bi-star-fill text-warning ms-1 small-11 pb-1"></i></h6>
                                            <span class="small text-muted">19</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar light-danger-bg " role="progressbar"
                                                style="width: 15%" aria-valuenow="15" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="progress-count mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold d-flex align-items-center">1<i
                                                    class="bi bi-star-fill text-warning ms-1 small-11 pb-1"></i></h6>
                                            <span class="small text-muted">39</span>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-careys-pink" role="progressbar"
                                                style="width: 10%" aria-valuenow="10" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="customer-like mt-5">
                                        <h6 class="mb-0 fw-bold ">What Customers Like</h6>
                                        <ul class="list-group mt-3">
                                            <li class="list-group-item d-flex">
                                                <div class="number border-end pe-2 fw-bold">
                                                    <strong class="color-light-success">1</strong>
                                                </div>
                                                <div class="cs-text flex-fill ps-2">
                                                    <span>Fun Factor</span>
                                                </div>
                                                <div class="vote-text">
                                                    <span class="text-muted">72 votes</span>
                                                </div>
                                            </li>
                                            <li class="list-group-item d-flex">
                                                <div class="number border-end pe-2 fw-bold">
                                                    <strong class="color-light-success">2</strong>
                                                </div>
                                                <div class="cs-text flex-fill ps-2">
                                                    <span>Great Value</span>
                                                </div>
                                                <div class="vote-text">
                                                    <span class="text-muted">52 votes</span>
                                                </div>
                                            </li>
                                            <li class="list-group-item d-flex">
                                                <div class="number border-end pe-2 fw-bold">
                                                    <strong class="color-light-success">3</strong>
                                                </div>
                                                <div class="cs-text flex-fill ps-2">
                                                    <span>My Task</span>
                                                </div>
                                                <div class="vote-text">
                                                    <span class="text-muted">35 votes</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="customer-like mt-5">
                                        <h6 class="mb-0 fw-bold ">What Need Improvement</h6>
                                        <ul class="list-group mt-3">
                                            <li class="list-group-item d-flex">
                                                <div class="number border-end pe-2 fw-bold">
                                                    <strong class="color-careys-pink">1</strong>
                                                </div>
                                                <div class="cs-text flex-fill ps-2">
                                                    <span>Value for Money</span>
                                                </div>
                                                <div class="vote-text">
                                                    <span class="text-muted">12 votes</span>
                                                </div>
                                            </li>
                                            <li class="list-group-item d-flex">
                                                <div class="number border-end pe-2 fw-bold">
                                                    <strong class="color-careys-pink">2</strong>
                                                </div>
                                                <div class="cs-text flex-fill ps-2">
                                                    <span>Customer service</span>
                                                </div>
                                                <div class="vote-text">
                                                    <span class="text-muted">8 votes</span>
                                                </div>
                                            </li>
                                            <li class="list-group-item d-flex">
                                                <div class="number border-end pe-2 fw-bold">
                                                    <strong class="color-careys-pink">3</strong>
                                                </div>
                                                <div class="cs-text flex-fill ps-2">
                                                    <span>Loding Item</span>
                                                </div>
                                                <div class="vote-text">
                                                    <span class="text-muted">2 votes</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <ul class="list-unstyled mb-4 res-set">
                            @forelse ($absences as $absence)
                                @php
                                    $employee = $absence->duty->employee;
                                    $absence_type = $absence->absence_type;
                                @endphp
                                <li class="card mb-2">
                                    @include('pages.admin.attendances.absences.request')
                                </li> <!-- .Card End -->
                            @empty
                                @switch($stage)
                                    @case('IN_PROGRESS')
                                        <li class="card mb-2"><x-no-data color="warning"
                                                text="Aucune Demande En Cours De Traitement" />
                                        </li>
                                    @break

                                    @default
                                        <li class="card mb-2"><x-no-data color="warning" text="Aucune Demande En Attente" />
                                        </li>
                                @endswitch
                            @endforelse


                        </ul>
                        {!! $absences->links() !!}
                    </div>
                </div><!-- Row End -->
            </div>
        </div>
    </div>
</div><!-- Row End -->
