@extends('modules.recours.pages.base')
@section('admin-content')
    <div class="body d-flex py-lg-3 py-md-2">
        <div class="container-xxl">

            <div class="row g-3 d-flex">
                <div class="col-xl-8 col-lg-12 col-md-12 d-flex flex-grow-1">
                    <div class="w-100">
                        <div class="row g-3 mb-3 row-deck">
                            <div class="col-md-12 col-lg-3 col-xl-3 col-xxl-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail color-defult"><i
                                                    class="icofont-paperclip fs-5"></i></div>
                                            <div class="flex-fill ms-4">
                                                <h5 class="mb-0 ">{{ $on_going_count }}</h5>
                                                <div class="text-muted">En Analyse</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-3 col-xl-3 col-xxl-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail color-defult"><i
                                                    class="icofont-paperclip fs-5"></i></div>
                                            <div class="flex-fill ms-4">
                                                <h5 class="mb-0 ">{{ $suspended_count }}</h5>
                                                <div class="text-muted">Suspendus</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-3 col-xl-3 col-xxl-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail color-defult"><i
                                                    class="icofont-paperclip fs-5"></i></div>
                                            <div class="flex-fill ms-4">
                                                <h5 class="mb-0 ">{{ $accepted_count }}</h5>
                                                <div class="text-muted">Recevables</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-3 col-xl-3 col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail color-defult"><i
                                                    class="icofont-paperclip fs-5"></i></div>
                                            <div class="flex-fill ms-4">
                                                <h5 class="mb-0 ">{{ $rejected_count }}</h5>
                                                <div class="text-muted">Non Recevables</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Row End -->

                        <div>
                            <div class='text-center mb-3 mt-4'>
                                <!-- Formulaire de filtrage -->
                                <form method="GET" action="{{ route('recours.home') }}"
                                    class="d-flex justify-content-center">

                                    <div class="">
                                        <label for="start_date">De :</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"
                                            value="{{ request('start_date') }}">
                                    </div>

                                    <div class="mx-2">
                                        <label for="end_date">À :</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control"
                                            value="{{ request('end_date') }}">
                                    </div>

                                    <div class="d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                                    </div>
                                </form>
                            </div>
                            {!! $chart->container() !!}
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-12 col-md-12 d-flex flex-column w-auto">
                    <div class="card mb-3">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold ">Récents</h6>
                        </div>
                        <div class="card-body vh-100">
                            @forelse($on_going as $appeal)
                                <hr>
                                <div class='mb-3'>
                                    <div class='fw-bold'><a href="{{ route('recours.show', $appeal->id) }}"><span
                                                class='fs-5'>@</span>{{ $appeal->dac->reference }}</a></div>
                                    <div>
                                        @if ($appeal->analyse_status == 'RECEVABLE')
                                            Etude: <span
                                                class="fw-bold text-success p-2">{{ $appeal->analyse_status }}</span>
                                        @elseif($appeal->analyse_status == 'IRRECEVABLE')
                                            Etude: <span
                                                class="fw-bold text-danger p-2">{{ $appeal->analyse_status }}</span>
                                        @else
                                            Etude: <span
                                                class="fw-bold text-warning p-2">{{ $appeal->analyse_status }}</span>
                                        @endif
                                    </div>
                                    <div>Decision: <span
                                            class='fw-bold text-info p-2'>{{ $appeal->decided->decision ?? $appeal->suspended->decision ?? 'N/A' }}</span>
                                    </div>
                                    <div>Delai: {{ $appeal->day_count }} jrs</div>
                                </div>
                            @empty
                                <p>Tout est traité.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    </div>
@endsection
@push('plugins-js')
@endpush
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const startDate = document.getElementById("start_date");
            const endDate = document.getElementById("end_date");

            // Lorsque la date de début change
            startDate.addEventListener("change", function() {
                if (endDate.value < startDate.value) {
                    endDate.value = startDate.value;
                    // console.log('dans html start : '+ startDate.value);
                    // console.log('dans html end : '+ endDate.value);

                }
                endDate.min = startDate.value; // Empêche de sélectionner une date antérieure
            });

            // Lorsque la date de fin change
            endDate.addEventListener("change", function() {
                if (endDate.value < startDate.value) {
                    endDate.value = startDate.value;
                }
            });

        });
    </script>
    <!-- <script src="{{ asset('assets/js/page/hr.js') }}"></script> -->
    <!-- Charger le script de Laravel Charts -->
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endpush
