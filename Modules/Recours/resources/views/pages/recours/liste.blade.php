@extends('pages.admin.recours_base')
@section('plugins-style')
@endsection
@section('admin-content')
            <div class="container-xxl">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card border-0 no-bg">
                        <h3 class=" fw-bold flex-fill mb-0 mt-sm-0 text-center">Nos Recours(Total :{{$recours_count}})</h3>
    
                            <div class="card-header px-4 d-sm-flex align-items-center justify-content-between border-bottom mt-4 mx-5">
                                <a href="{{route('recours.new')}}">

                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="">
                                        <i class="icofont-plus-circle me-2 fs-6"></i> Ajouter
                                    </button>
                                </a>
                                <div class='d-flex justify-content-between align-items-center p-2 gap-2'>
                                    <div class='d-flex justify-content-between align-items-center p-2 gap-2'>
                                        <label for="startDate" class='fs-6'>De: </label>
                                        <input type="date" id="startDate" class='form-control'>
                                    </div>
                                    <div class='d-flex justify-content-between align-items-center p-2 gap-2'>
                                        <label for="endDate" class='fs-6'>À: </label>
                                        <input type="date" id="endDate" class='form-control'>
                                    </div>
                                    <!-- <button type="submit" class='btn btn-secondary'>
                                        <i class="icofont-search fs-6 mx-1"></i>Rechercher
                                    </button> -->
                                </div>

                                <!-- Icône avec Dropdown -->
                        <div class="dropdown" >
                            <i class="icofont-settings fs-2" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                            
                            <div class="dropdown-menu p-3" id='filterContainer'>
                                <!-- Status -->
                                <strong>Etude Status</strong>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="statusEnCours" value='EN_COURS' name='filterStatus'>
                                    <label class="form-check-label" for="etude">En cours</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="statusAccepte" value='ACCEPTE' name='filterStatus'>
                                    <label class="form-check-label" for="statusAccepte">Accepté</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="statusRejete" value='REJETE' name='filterStatus'>
                                    <label class="form-check-label" for="statusRejete">Rejeté</label>
                                </div>

                                <hr class="my-2">

                                <!-- Decisions -->
                                <strong>Décisions</strong>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionEnCours" value='EN COURS' name='filterStatus'>
                                    <label class="form-check-label" for="decisionEnCours">En cours</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionForclusion" value='FORCLUSION' name='filterStatus'>
                                    <label class="form-check-label" for="decisionForclusion">Forclusion</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionIrrecevable" value='IRRECEVABLE' name='filterStatus'>
                                    <label class="form-check-label" for="irrecevable">Irrécevabilité</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionFonde" value='FONDE' name='filterStatus'>
                                    <label class="form-check-label" for="decisionFonde">Fondé</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionNonFonde" value='NON FONDE' name='filterStatus'>
                                    <label class="form-check-label" for="decisionNonFonde">Non Fondé</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionDesistement" value='DESISTEMENT' name='filterStatus'>
                                    <label class="form-check-label" for="statusDesistement">Désistement</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="statusIncompetence" value='HORS COMPETENCE' name='filterStatus'>
                                    <label class="form-check-label" for="statusIncompetence">Hors Compétence</label>
                                </div>
                                    </div>
                                </div>

                
                            </div>

                            <!--  -->
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
                                <!-- <h3 class=" fw-bold flex-fill mb-0 mt-sm-0">Nos Recours(Total :14)</h3> -->

                                <!-- <div class='d-flex justify-content-between align-items-center p-4'>
                                        <input type="date" name="" id="" class='form-control mx-2'>
                                        <input type="date" name="" id="" class='form-control mx-2'>
                                        <button type="submit" class='btn-secondary'>Rechercher</button>
                                    </div> -->
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
                                        <input type="text" id="searchInput" placeholder="Rechercher" class='form-control me-2'>
                                    </div>
                                    
                                </div>
                                <div class='table-responsive'>
                                    <table id="recours" class="table table-hover align-middle mb-0 ">
                                        <thead>
                                            <tr>
                                                <th>Marché</th>
                                                <th>Requérant</th>
                                                <th>Objet</th>
                                                <th>Dépôt le</th>
                                                <th>À(HEURE)</th>
                                                <th>Étude</th>
                                                <th>Décision</th>
                                                <th>Détails</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                
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
        
     <!-- M -->

      
@endsection
@push('plugins-js')
@endpush
@push('js')

<script>
document.addEventListener("DOMContentLoaded", function () {
    const startDate = document.getElementById("startDate");
    const endDate = document.getElementById("endDate");

    // Lorsque la date de début change
    startDate.addEventListener("change", function () {
        if (endDate.value < startDate.value) {
            endDate.value = startDate.value;
            // console.log('dans html start : '+ startDate.value);
            // console.log('dans html end : '+ endDate.value);
            
        }
        endDate.min = startDate.value; // Empêche de sélectionner une date antérieure
    });

    // Lorsque la date de fin change
    endDate.addEventListener("change", function () {
        if (endDate.value < startDate.value) {
            endDate.value = startDate.value;
        }
    });

});
</script>

<script src="{{ asset('app-js/personnel/paginator.js') }}"></script>
<script src="{{ asset('app-js/recours/list.js') }}"></script>


@endpush