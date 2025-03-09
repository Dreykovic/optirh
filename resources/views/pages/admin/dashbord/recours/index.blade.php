@extends('pages.admin.recours_base')
@section('admin-content')
    
    <div class="body d-flex py-lg-3 py-md-2">
        <div class="container-xxl">
           
            <div class="row g-3">
                <div class="col-xl-10 col-lg-12 col-md-12">

                        <div class="row g-3 mb-3 row-deck">
                            <div class="col-md-12 col-lg-4 col-xl-4 col-xxl-4">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail bg-lightyellow color-defult"><i class="icofont-paperclip fs-5"></i></div>
                                            <div class="flex-fill ms-4">
                                                <div class="">En cours</div>
                                                <h5 class="mb-0 ">{{$on_going_count}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4 col-xl-4 col-xxl-4">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail bg-success color-defult"><i class="icofont-paperclip fs-5"></i></div>
                                            <div class="flex-fill ms-4">
                                                <div class="">Acceptes</div>
                                                <h5 class="mb-0 ">{{$accepted_count}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4 col-xl-4 col-xxl-4">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail bg-danger color-defult"><i class="icofont-paperclip fs-5"></i></div>
                                            <div class="flex-fill ms-4">
                                                <div class="">Rejetes</div>
                                                <h5 class="mb-0 ">{{$rejected_count}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Row End -->
                        
                    <div>
                        <!--graphe  -->
                    </div>
                   
                </div>

                <div class="col-xl-2 col-lg-12 col-md-12">
                    <div class="card mb-3">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold ">Récents</h6>
                        </div>
                        <div class="card-body vh-100">
                            @forelse($on_going as $appeal)
                            <hr>
                            <div class='mb-3'>
                                <div class='fw-bold'><a href="{{route('recours.show', $appeal->id)}}"><span class='fs-5'>@</span>{{$appeal->dac->reference}}</a></div>
                                <div>
                                    @if($appeal->analyse_status == 'ACCEPTE')
                                        Etude: <span class="fw-bold text-success p-2">{{$appeal->analyse_status}}</span>
                                    @elseif($appeal->analyse_status == 'REJETE')
                                        Etude: <span class="fw-bold text-danger p-2">{{$appeal->analyse_status}}</span>
                                    @else
                                        Etude: <span class="fw-bold text-warning p-2">{{$appeal->analyse_status}}</span>
                                    @endif
                                </div>
                                <div>Decision: <span class='fw-bold text-info p-2'>{{$appeal->decision->decision ?? 'N/A'}}</span></div>
                                <div>Delai: {{$appeal->day_count}}</div>
                            </div>
                            
                            @empty 
                                <p>Tout est traité.</p>
                            @endforelse
                        </div>
                    </div>
                  
                </div>
            </div><!-- Row End -->
        
        </div>
    </div>

      
@endsection
@push('plugins-js')
    <script src="{{ asset('assets/bundles/apexcharts.bundle.js') }}"></script>
@endpush
@push('js')
    <script src="{{ asset('assets/js/page/hr.js') }}"></script>
@endpush
