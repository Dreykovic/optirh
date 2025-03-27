@extends('modules.opti-hr.pages.base')
@section('plugins-style')
<style>
        .form-text {
            font-size: 0.875em;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .required:after {
            content: " *";
            color: red;
        }
</style>
@endsection
@section('admin-content')
    <div class="container-xxl">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="card border-0 mb-4 no-bg">
                    <div class="card-header py-3 px-0 d-sm-flex align-items-center  justify-content-between border-bottom">
                        <h3 class=" fw-bold flex-fill mb-0 mt-sm-0">Nos Contrats</h3>
                        <button type="button" class="btn btn-dark me-1 mt-1 w-sm-100" data-bs-toggle="modal"
                            data-bs-target="#addEmpModal"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter</button>
                        <ul class="nav nav-tabs tab-body-header rounded ms-3 prtab-set w-sm-100" role="tablist">
                            <li class="nav-item"><a class="nav-link active" href="#ON_GOING" role="tab">En Cours</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#SUSPENDED" role="tab">Suspendus</a></li>
                            <li class="nav-item"><a class="nav-link" href="#ENDED" role="tab">Terminés</a></li>
                            <li class="nav-item"><a class="nav-link" href="#RESIGNED" role="tab">Démissionnés</a></li>
                            <li class="nav-item"><a class="nav-link" href="#DISMISSED" role="tab">Licenciés</a></li>
                            <li class="nav-item"><a class="nav-link" href="#DELETED" role="tab">Supprimés</a></li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
        <div class="body d-flex py-lg-3 py-md-2">
            <div class="container-xxl">
                <div class="row clearfix g-3">
                    <div class="col-sm-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class='d-flex justify-content-between'>
                                    <div class='d-flex justify-content-between align-items-center'>
                                        <span>Afficher</span>
                                        <select id="limitSelect" class='form-select mx-2'>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                        </select>
                                        <span>éléments</span>
                                    </div>
                                    <div class='d-flex justify-content-between align-items-center'>
                                        <label for="searchInput" class='me-2'>Rechercher: </label>
                                        <input type="text" id="searchInput" placeholder="Rechercher"
                                            class='form-control me-2'>
                                    </div>

                                </div>

                                <table id="contrats" class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Employe</th>
                                            <th>Direction</th>
                                            <th>Poste</th>
                                            <th>Date Embauche</th>
                                            <th>Type</th>
                                            <th>Sole Congé</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div id="pagination" class='mt-3'></div>
                                <!--  -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Members-->
    @include('modules.opti-hr.pages.personnel.contrats.add')
@endsection
@push('plugins-js')
@endpush
@push('js')
    <script src="{{ asset('app-js/personnel/paginator.js') }}"></script>
    <script src="{{ asset('app-js/personnel/contrats/list.js') }}"></script>
    <script src="{{ asset('app-js/personnel/contrats/actions.js') }}"></script>

    <script src="{{ asset('app-js/personnel/jobs/loadJobs.js') }}"></script>
    <script src="{{ asset('app-js/personnel/membres/create.js') }}"></script><!-- using the same js code for duty add -->
@endpush
