@extends('pages.admin.base')
@section('plugins-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
@endsection
@section('admin-content')
  <!-- Body: Body -->
  <div class="body d-flex py-lg-3 py-md-2">
            <div class="container-xxl">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card border-0 mb-4 no-bg">
                            <div class="card-header py-3 px-0 d-flex align-items-center  justify-content-between border-bottom">
                                <h3 class=" fw-bold flex-fill mb-0">Profile</h3>
                            </div>
                        </div>
                    </div>
                </div><!-- Row End -->
                <div class="row g-3">
                    <div class="col-xl-8 col-lg-12 col-md-12">
                        <div class="card teacher-card  mb-3">
                            <div class="card-body  d-flex teacher-fulldeatil">
                                <div class="profile-teacher pe-xl-4 pe-md-2 pe-sm-4 pe-0 text-center w220 mx-sm-0 mx-auto">
                                    
                                    <i class="icofont icofont-{{ $employee->gender === 'FEMALE' ? 'businesswoman' : 'business-man-alt-2' }} avatar xl rounded-circle img-thumbnail shadow-sm" style="font-size: 100px; width: 140px; height: 140px;"></i>
                                   
                                    <div class="about-info d-flex align-items-center mt-3 justify-content-center flex-column">
                                        <h6 class="mb-0 fw-bold d-block small-11 text-muted">{{$employee->gender==='FEMALE'?'Femme':'Homme'}}</h6>
                                        <!-- <span class="text-muted small">Employee Id : 00001</span> -->
                                    </div>
                                </div>
                                <div class="teacher-info border-start ps-xl-4 ps-md-3 ps-sm-4 ps-4 w-100">
                                    <h6  class="mb-0 mt-2  fw-bold d-block fs-6">{{$employee->last_name}} {{$employee->first_name}}</h6>
                                    <span class="py-1 fw-bold small-11 mb-0 mt-1 text-muted mb-4">{{$duty ? $duty->job->title :'Pas de contrat en cours'}}</span>
                                    <!-- <p class="mt-2 small">The purpose of lorem ipsum is to create a natural looking block of text (sentence, paragraph, page, etc.) that doesn't distract from the layout. A practice not without controversy</p> -->
                                    <div class="row g-2 pt-2">
                                        <div class="col-xl-5">
                                            <div class="d-flex align-items-center">
                                                <i class="icofont-ui-touch-phone"></i>
                                                <span class="ms-2 small">{{$employee->phone_number}} </span>
                                            </div>
                                        </div>
                                        <div class="col-xl-5">
                                            <div class="d-flex align-items-center">
                                                <i class="icofont-email"></i>
                                                <span class="ms-2 small">{{$employee->email}}</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-5">
                                            <div class="d-flex align-items-center">
                                                <i class="icofont-birthday-cake"></i>
                                                <span class="ms-2 small">{{$employee->birth_day?? 'Né le'}}</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-5">
                                            <div class="d-flex align-items-center">
                                                <i class="icofont-address-book"></i>
                                                <span class="ms-2 small">{{$employee->address1}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <h6 class="fw-bold  py-3 mb-3">Current Work Project</h6> -->
                        <!-- <div class="teachercourse-list">
                            <div class="row g-3 gy-5 py-3 row-deck">
                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mt-5">
                                                <div class="lesson_name">
                                                    <div class="project-block light-info-bg">
                                                        <i class="icofont-paint"></i>
                                                    </div>
                                                    <span class="small text-muted project_name fw-bold"> Social Geek Made </span>
                                                    <h6 class="mb-0 fw-bold  fs-6  mb-2">UI/UX Design</h6>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-list avatar-list-stacked pt-2">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar2.jpg" alt="">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar1.jpg" alt="">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar3.jpg" alt="">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar4.jpg" alt="">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar8.jpg" alt="">
                                                </div>
                                            </div>
                                            <div class="row g-2 pt-4">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="icofont-paper-clip"></i>
                                                        <span class="ms-2">5 Attach</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="icofont-sand-clock"></i>
                                                        <span class="ms-2">4 Month</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="icofont-group-students "></i>
                                                        <span class="ms-2">5 Members</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="icofont-ui-text-chat"></i>
                                                        <span class="ms-2">10</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dividers-block"></div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h4 class="small fw-bold mb-0">Progress</h4>
                                                <span class="small light-danger-bg  p-1 rounded"><i class="icofont-ui-clock"></i> 35 Days Left</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 25%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                                <div class="progress-bar bg-secondary ms-1" role="progressbar" style="width: 25%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                <div class="progress-bar bg-secondary ms-1" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mt-5">
                                                <div class="lesson_name">
                                                    <div class="project-block bg-lightgreen">
                                                        <i class="icofont-vector-path"></i>
                                                    </div>
                                                    <span class="small text-muted project_name fw-bold"> Practice to Perfect </span>
                                                    <h6 class="mb-0 fw-bold  fs-6  mb-2">Website Design</h6>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-list avatar-list-stacked pt-2">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar2.jpg" alt="">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar1.jpg" alt="">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar3.jpg" alt="">
                                                    <img class="avatar rounded-circle sm" src="assets/images/xs/avatar4.jpg" alt="">
                                                </div>
                                            </div>
                                            <div class="row g-2 pt-4">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="icofont-paper-clip"></i>
                                                        <span class="ms-2">4 Attach</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="icofont-sand-clock"></i>
                                                        <span class="ms-2">1 Month</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="icofont-group-students "></i>
                                                        <span class="ms-2">4 Members</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <i class="icofont-ui-text-chat"></i>
                                                        <span class="ms-2">3</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dividers-block"></div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h4 class="small fw-bold mb-0">Progress</h4>
                                                <span class="small light-danger-bg  p-1 rounded"><i class="icofont-ui-clock"></i> 15 Days Left</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 25%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                                <div class="progress-bar bg-secondary ms-1" role="progressbar" style="width: 25%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                                <div class="progress-bar bg-secondary ms-1" role="progressbar" style="width: 39%" aria-valuenow="39" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row g-3">
                            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header py-3 d-flex justify-content-between">
                                        <h6 class="mb-0 fw-bold ">Informations Personnelles</h6>
                                        <button type="button" class="btn p-0" data-bs-toggle="modal" data-bs-target="#updatePersInfoModal"><i class="icofont-edit text-primary fs-6"></i></button>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Nationalité</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->nationality ?? '---'}}</span>
                                                </div>
                                            </li>
                                            <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Religion</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->religion ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Situation Matri.</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->marital_status ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Contact urgence</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->emergency_contact ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Ville</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->city ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Quartier</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->state ?? '---'}}</span>
                                                </div>
                                             </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header py-3 d-flex justify-content-between">
                                        <h6 class="mb-0 fw-bold ">Compte Bancaire</h6>
                                        <button type="button" class="btn p-0" data-bs-toggle="modal" data-bs-target="#updateBankInfoModal"><i class="icofont-edit text-primary fs-6"></i></button>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Bank Name</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->bank_name ?? '---'}}</span>
                                                </div>
                                            </li>
                                            <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">No. Compte</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->rib ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Code Banque</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->code_bank ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Code Guichet</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->code_guichet ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">IBAN</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->iban ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap mb-3">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Swift</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->swift ?? '---'}}</span>
                                                </div>
                                             </li>
                                             <li class="row flex-wrap">
                                                <div class="col-6"> 
                                                    <span class="fw-bold">Clé RIB</span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="text-muted">{{$employee->cle_rib ?? '---'}}</span>
                                                </div>
                                             </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class='d-flex justify-content-between'>
                                    <h6 class="fw-bold mb-3 text-danger">Factures</h6>
                                    <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#addFileModal">Nouveau</button>
                                </div>
                                <div class="d-flex mb-3 justify-content-between">
                                    <!-- Champ de recherche -->
                                    <input type="text" id="searchInput" class="form-control me-2" placeholder="Rechercher...">
                                    <input type="text" value='{{$employee->id}}' name='employee_id' id='employeeId' hidden>
                                    <!-- Choix du nombre d'éléments par page -->
                                    <select id="limitSelect" class="form-select ms-2">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                                <div class="flex-grow-1" id="fileList"></div>
                                
                                <!-- Pagination -->
                                <div id="pagination" class="d-flex justify-content-center mt-3"></div>
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
                                <div class="d-flex align-items-center flex-column flex-sm-column flex-md-column flex-lg-row">
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
                                <div class="d-flex align-items-center flex-column flex-sm-column flex-md-column flex-lg-row">
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
                                                      
                                                    <span>All operations permission</span>
                                                   </a>
                                                   
                                                </li>
                                                <li>
                                                     <a class="dropdown-item" href="#">
                                                        <i class="fs-6 p-2 me-1"></i>
                                                           <span>Only Invite & manage team</span>
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
                                <div class="d-flex align-items-center flex-column flex-sm-column flex-md-column flex-lg-row">
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
                                                      
                                                    <span>All operations permission</span>
                                                   </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fs-6 p-2 me-1"></i>
                                                           <span>Only Invite & manage team</span>
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

        <!-- Modal Modal Center-->
    <div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
                <div class="modal-body">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Nouveau Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form  id='modelAddForm' enctype="multipart/form-data" data-model-add-url="{{ route('files.upload',['employeeId' => $employee->id]) }}">
                        @csrf 
                        <div class="">
                            <label for="files" class="form-label">Choisir des fichiers (PDF, IMAGE) :</label>
                            <input type="file" name="files[]" id="files" class="form-control form-control-lg" accept=".pdf, .png, .jpeg, .jpg" multiple>
                        </div>

                        <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-block lift text-uppercase btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-sm btn-block lift text-uppercase btn-primary" atl="Ajouter Emp" id="modelAddBtn"
                                        data-bs-dismiss="modal">
                                        <span class="normal-status">
                                            Enregister
                                        </span>
                                        <span class="indicateur d-none">
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            Un Instant...
                                        </span>
                                    </button>
                                </div>
                    </form>

                </div>
                
            </div>
        </div>
    </div>

    <!--  -->
    
       
    @include('pages.admin.personnel.membres.edits.edit-pers-info')
    @include('pages.admin.personnel.membres.edits.edit-bank-info')
    
@endsection
@push('plugins-js')

@endpush
@push('js')
<script src="{{ asset('app-js/crud/post.js') }}"></script>
<script src="{{ asset('app-js/crud/delete.js') }}"></script>
<script src="{{ asset('app-js/employee/paginator.js') }}"></script>
<script src="{{ asset('app-js/crud/put.js') }}"></script>

<script>
    const employeeId = document.getElementById('employeeId').value;
    const paginator = new Paginator({
        apiUrl: `/api/files/${employeeId}`, // URL de l'API
            renderElement: document.getElementById('fileList'), // Élément où afficher les données
            renderCallback: renderFiles, // Fonction pour rendre les fichiers
            searchInput: document.getElementById('searchInput'), // Input de recherche
            directorInput:document.getElementById('directorInput'),
            limitSelect: document.getElementById('limitSelect'), // Sélecteur de limite
            paginationElement: document.getElementById('pagination'), // Élément pour la pagination
        
        });

        // Fonction de rendu pour les fichiers
        function renderFiles(files) {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';

            if (files.length === 0) {
                fileList.innerHTML = '<div class="alert alert-warning">Aucun fichier trouvé.</div>';
            } else {
                files.forEach(file => {
                    const fileElement = document.createElement('div');
                    fileElement.className = 'py-2 d-flex align-items-center border-bottom';
                    fileElement.innerHTML = `
                        <div class="d-flex ms-3 align-items-center flex-fill">
                            <span class="avatar small-11 ${file.icon_class} rounded-circle text-center d-flex align-items-center justify-content-center">
                                <i class="${file.icon} fs-5"></i>
                            </span>
                            <div class="d-flex flex-column ps-3" style="max-width: 200px;">
                                <h6 class="fw-bold mb-0 small-14 text-truncate text-muted" title="${file.display_name}">
                                    ${file.display_name}
                                </h6>
                            </div>
                        </div>
                        <div class="btn-group">
                            <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                            <ul class="dropdown-menu border-0 shadow bg-primary">
                                <li><a data-bs-target="#updateFileModal${file.id}" data-bs-toggle="modal" class="dropdown-item text-light" ><i class="icofont-edit text-success m-2"></i>Renommer</a></li>
                               
                                <li>
                                    <form action="/files/delete/${file.id}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="dropdown-item text-light">Supprimer</button>
                                    </form>
                                </li>
                                <li><a class="dropdown-item text-light" href="${file.url}" target="_blank">Ouvrir</a></li>
                            </ul>
                        </div>

                        <div class="modal fade" id="updateFileModal${file.id}" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    
                                    <div class="modal-body modelUpdateFormContainer" id="updateFileForm${file.id}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="">Modifier Document</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form data-model-update-url="/files/rename/${file.id}">
                                            @csrf
                                                <input type="hidden" name="_method" value="PUT">
                                            <div class="">
                                                <label for="files" class="form-label">Nouveau nom du fichier:</label>
                                                <input type="text" value="${file.display_name} ${file.id}" name="new_name" id="files" class="form-control form-control">
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Modifier Absence Type"
                                                    data-bs-dismiss="modal">
                                                    <span class="normal-status">
                                                        Enregistrer4
                                                    </span>
                                                    <span class="indicateur d-none">
                                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                        Un Instant...
                                                    </span>
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    `;
                    fileList.appendChild(fileElement);
                });
            }
        }

</script>


@endpush
