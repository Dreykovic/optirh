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
                                <h3 class=" fw-bold flex-fill mb-0">Mes Factures</h3>
                            </div>
                        </div>
                    </div>
                </div><!-- Row End -->
                <div class="row g-3">
                   
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex mb-3 justify-content-between">
                                    <!-- Champ de recherche -->
                                    <input type="text" value='{{$employee->id}}' name='employee_id' id='employeeId' hidden>
                                    <!-- Choix du nombre d'éléments par page -->
                                    <div class='d-flex justify-content-between align-items-center'>
                                    <span>Afficher</span>
                                    <select id="limitSelect" class="form-select mx-2">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>
                                    <span>Éléments</span>
                                    </div>
                                    <input type="text" id="searchInput" class="form-control w-25" placeholder="Rechercher...">
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


       
@endsection
@push('plugins-js')

@endpush
@push('js')
<script src="{{ asset('app-js/employee/paginator.js') }}"></script>

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
                            <div class="d-flex flex-column ps-3">
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
