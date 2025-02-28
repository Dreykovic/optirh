@extends('pages.admin.base')
@section('plugins-style')
@endsection
@section('admin-content')
            <div class="container-xxl">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card border-0 no-bg">
                        <h3 class=" fw-bold flex-fill mb-0 mt-sm-0 text-center">Nos Recours(Total :14)</h3>
    
                            <div class="card-header px-4 d-sm-flex align-items-center justify-content-between border-bottom mt-4 mx-5">
                                <a href="{{route('recours.new')}}">

                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="">
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
                                    <button type="submit" class='btn btn-secondary'>
                                        <i class="icofont-search fs-6 mx-1"></i>Rechercher
                                    </button>
                                </div>

                                <!-- Icône avec Dropdown -->
                        <div class="dropdown">
                            <i class="icofont-settings fs-2" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                            
                            <div class="dropdown-menu p-3">
                                <!-- Status -->
                                <strong>Etude Status</strong>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="etude">
                                    <label class="form-check-label" for="etude">En cours</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="statusAccepte">
                                    <label class="form-check-label" for="statusAccepte">Accepté</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="statusRejete">
                                    <label class="form-check-label" for="statusRejete">Rejeté</label>
                                </div>

                                <hr class="my-2">

                                <!-- State -->
                                <!-- <strong>State</strong>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stateEncours">
                                    <label class="form-check-label" for="stateEncours">En cours</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stateSuspendu">
                                    <label class="form-check-label" for="stateSuspendu">Suspendu</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stateTermine">
                                    <label class="form-check-label" for="stateTermine">Terminé</label>
                                </div>

                                <hr class="my-2"> -->

                                <!-- Decisions -->
                                <strong>Décisions</strong>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionEnCours">
                                    <label class="form-check-label" for="decisionEnCours">En cours</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionForclusion">
                                    <label class="form-check-label" for="decisionForclusion">Forclusion</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionFonde">
                                    <label class="form-check-label" for="decisionFonde">Fondé</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="decisionNonFonde">
                                    <label class="form-check-label" for="decisionNonFonde">Non Fondé</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="statusDesistement">
                                    <label class="form-check-label" for="statusDesistement">Désistement</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="statusIncompetence">
                                    <label class="form-check-label" for="statusIncompetence">Hors Compétence</label>
                                </div>
                                    </div>
                                </div>

                                <!-- <i class="icofont-settings fs-2"></i> -->
                                <!-- <i class="icofont-settings-alt fs-1"></i> -->
                                <!-- <select id="directorInput" class='btn btn-dark me-1 mt-1 w-sm-100 p-2'>
                                    <option value="">gg</option>
                                    <option value="" selected>Directions</option>
                                </select> -->
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
                                
                                <table id="recours" class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Marché</th>
                                            <th>Requérant</th>
                                            <th>Objet</th>
                                            <th>Dépôt le</th>
                                            <th>Étude</th>
                                            <th>Décision</th>
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
       <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title  fw-bold" id="addUserLabel">Employee Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="inviteby_email">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email address" id="exampleInputEmail1" aria-describedby="exampleInputEmail1">
                        <button class="btn btn-dark" type="button" id="button-addon2">Sent</button>
                    </div>
                </div>
                <div class="members_list">
                    <h6 class="fw-bold ">Employee </h6>
                    <ul class="list-unstyled list-group list-group-custom list-group-flush mb-0">
                        <li class="list-group-item py-3 text-center text-md-start">
                            <div class="d-flex align-items-center flex-column flex-sm-column flex-md-row">
                                <div class="no-thumbnail mb-2 mb-md-0">
                                    <img class="avatar lg rounded-circle" src="assets/images/xs/avatar2.jpg" alt="">
                                </div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <h6 class="mb-0  fw-bold">Rachel Carr(you)</h6>
                                    <span class="text-muted">rachel.carr@gmail.com</span>
                                </div>
                                <div class="members-action">
                                    <span class="members-role ">Admin</span>
                                    <div class="btn-group">
                                        <button type="button" class="btn bg-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="icofont-ui-settings  fs-6"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                          <li><a class="dropdown-item" href="#"><i class="icofont-ui-password fs-6 me-2"></i>ResetPassword</a></li>
                                          <li><a class="dropdown-item" href="#"><i class="icofont-chart-line fs-6 me-2"></i>ActivityReport</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item py-3 text-center text-md-start">
                            <div class="d-flex align-items-center flex-column flex-sm-column flex-md-row">
                                <div class="no-thumbnail mb-2 mb-md-0">
                                    <img class="avatar lg rounded-circle" src="assets/images/xs/avatar3.jpg" alt="">
                                </div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <h6 class="mb-0  fw-bold">Lucas Baker<a href="#" class="link-secondary ms-2">(Resend invitation)</a></h6>
                                    <span class="text-muted">lucas.baker@gmail.com</span>
                                </div>
                                <div class="members-action">
                                    <div class="btn-group">
                                        <button type="button" class="btn bg-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Members
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                          <li>
                                              <a class="dropdown-item" href="#">
                                                <i class="icofont-check-circled"></i>
                                                    Member
                                                <span>Can view, edit, delete, comment on and save files</span>
                                               </a>
                                               
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fs-6 p-2 me-1"></i>
                                                        Admin
                                                    <span>Member, but can invite and manage team members</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn bg-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="icofont-ui-settings  fs-6"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                          <li><a class="dropdown-item" href="#"><i class="icofont-delete-alt fs-6 me-2"></i>Delete Member</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item py-3 text-center text-md-start">
                            <div class="d-flex align-items-center flex-column flex-sm-column flex-md-row">
                                <div class="no-thumbnail mb-2 mb-md-0">
                                    <img class="avatar lg rounded-circle" src="assets/images/xs/avatar8.jpg" alt="">
                                </div>
                                <div class="flex-fill ms-3 text-truncate">
                                    <h6 class="mb-0  fw-bold">Una Coleman</h6>
                                    <span class="text-muted">una.coleman@gmail.com</span>
                                </div>
                                <div class="members-action">
                                    <div class="btn-group">
                                        <button type="button" class="btn bg-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Members
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                          <li>
                                              <a class="dropdown-item" href="#">
                                                <i class="icofont-check-circled"></i>
                                                    Member
                                                <span>Can view, edit, delete, comment on and save files</span>
                                               </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fs-6 p-2 me-1"></i>
                                                        Admin
                                                    <span>Member, but can invite and manage team members</span>
                                                   </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button type="button" class="btn bg-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="icofont-ui-settings  fs-6"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                              <li><a class="dropdown-item" href="#"><i class="icofont-ui-password fs-6 me-2"></i>ResetPassword</a></li>
                                              <li><a class="dropdown-item" href="#"><i class="icofont-chart-line fs-6 me-2"></i>ActivityReport</a></li>
                                              <li><a class="dropdown-item" href="#"><i class="icofont-delete-alt fs-6 me-2"></i>Suspend member</a></li>
                                              <li><a class="dropdown-item" href="#"><i class="icofont-not-allowed fs-6 me-2"></i>Delete Member</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
       </div>

      
@endsection
@push('plugins-js')
@endpush
@push('js')

<script src="{{ asset('app-js/personnel/paginator.js') }}"></script>
<script src="{{ asset('app-js/recours/list.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const startDate = document.getElementById("startDate");
    const endDate = document.getElementById("endDate");

    // // Mettre la date d'aujourd'hui par défaut dans les champs
    // const today = new Date().toISOString().split("T")[0];
    // startDate.value = today;
    // endDate.value = today;

    // Lorsque la date de début change
    startDate.addEventListener("change", function () {
        if (endDate.value < startDate.value) {
            endDate.value = startDate.value;
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

@endpush