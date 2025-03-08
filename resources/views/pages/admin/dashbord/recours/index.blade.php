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
                                                <h5 class="mb-0 ">122</h5>
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
                                                <h5 class="mb-0 ">376</h5>
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
                                                <h5 class="mb-0 ">74</h5>
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
