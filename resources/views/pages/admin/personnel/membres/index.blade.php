@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
@endsection
@section('admin-content')

            <div class="container-xxl">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card border-0 mb-4 no-bg">
                            <div class="card-header py-3 px-0 d-sm-flex align-items-center  justify-content-between border-bottom">
                                <h3 class=" fw-bold flex-fill mb-0 mt-sm-0">Nos Employés(Total : {{$nbre_employees}})</h3>
                                <button type="button" class="btn btn-dark me-1 mt-1 w-sm-100" data-bs-toggle="modal" data-bs-target="#createemp"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter</button>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle mt-1  w-sm-100" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                        Direction
                                    </button>
                                    <ul class="dropdown-menu  dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="dropdown-item" href="#">All</a></li>
                                    <li><a class="dropdown-item" href="#">Task Assign Members</a></li>
                                    <li><a class="dropdown-item" href="#">Not Assign Members</a></li>
                                    </ul>
                                </div>
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
                                <table id="membres" class="table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <!-- <th>#</th> -->
                                            <th>Employe</th> 
                                            <th>Contact</th> 
                                            <th>Email</th>   
                                            <th>Adresse</th>   
                                            <th>Actions</th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($employees as $index => $employee)
                                        <tr data-href="{{ route('membres.show', ['employee' => $employee->id]) }}">
                                            <!-- <td>
                                                <span class="fw-bold">{{ $index + 1 }}</span>
                                            </td> -->
                                            <td>
                                                <div class='d-flex justify-content-start align-items-center'>
                                                    <div class="avatar rounded-circle d-flex justify-content-center align-items-center text-white" style="width: 50px; height: 50px; background-color: {{ generateColor($employee->first_name . $employee->last_name) }};">
                                                        @if ($employee)
                                                            <span class="fw-bold">
                                                                {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                                                            </span>
                                                        @else
                                                            <span class="fw-bold text-muted">N/A</span>
                                                        @endif
                                                    </div>
                                                    @if ($employee)
                                                        <span class="fw-bold ms-1">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                                                    @else
                                                        <span class="text-muted">Aucun directeur assigné</span>
                                                    @endif

                                                </div>
                                               <!--  -->
                                            </td>
                                            <td>
                                                {{ $employee->phone_number }}
                                            </td>
                                            <td>
                                                {{ $employee->email }}
                                            </td>
                                            <td>
                                                {{ $employee->address1 }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#depedit">
                                                        <i class="icofont-edit text-success"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary deleterow">
                                                        <i class="icofont-ui-delete text-danger"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                  </div>
                </div><!-- Row End -->
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

        <!-- Create Employee-->
        <div class="modal fade" id="createemp" tabindex="-1"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title  fw-bold" id="createprojectlLabel"> Add Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput877" class="form-label">Employee Name</label>
                            <input type="text" class="form-control" id="exampleFormControlInput877" placeholder="Explain what the Project Name">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput977" class="form-label">Employee Company</label>
                            <input type="text" class="form-control" id="exampleFormControlInput977" placeholder="Explain what the Project Name">
                        </div>
                        <div class="mb-3">
                            <label for="formFileMultipleoneone" class="form-label">Employee Profile</label>
                            <input class="form-control" type="file" id="formFileMultipleoneone" >
                        </div>
                        <div class="deadline-form">
                            <form>
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label for="exampleFormControlInput1778" class="form-label">Employee ID</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1778" placeholder="User Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="exampleFormControlInput2778" class="form-label">Joining Date</label>
                                        <input type="date" class="form-control" id="exampleFormControlInput2778">
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                <div class="col">
                                    <label for="exampleFormControlInput177" class="form-label">User Name</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput177" placeholder="User Name">
                                </div>
                                <div class="col">
                                    <label for="exampleFormControlInput277" class="form-label">Password</label>
                                    <input type="Password" class="form-control" id="exampleFormControlInput277" placeholder="Password">
                                </div>
                                </div> 
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label for="exampleFormControlInput477" class="form-label">Email ID</label>
                                        <input type="email" class="form-control" id="exampleFormControlInput477" placeholder="User Name">
                                    </div>
                                    <div class="col">
                                        <label for="exampleFormControlInput777" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput777" placeholder="User Name">
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label  class="form-label">Department</label>
                                        <select class="form-select" aria-label="Default select Project Category">
                                            <option selected>Web Development</option>
                                            <option value="1">It Management</option>
                                            <option value="2">Marketing</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label  class="form-label">Designation</label>
                                        <select class="form-select" aria-label="Default select Project Category">
                                            <option selected>UI/UX Design</option>
                                            <option value="1">Website Design</option>
                                            <option value="2">App Development</option>
                                            <option value="3">Quality Assurance</option>
                                            <option value="4">Development</option>
                                            <option value="5">Backend Development</option>
                                            <option value="6">Software Testing</option>
                                            <option value="7">Website Design</option>
                                            <option value="8">Marketing</option>
                                            <option value="9">SEO</option>
                                            <option value="10">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mb-3">          
                            <label for="exampleFormControlTextarea78" class="form-label">Description (optional)</label>
                            <textarea class="form-control" id="exampleFormControlTextarea78" rows="3" placeholder="Add any extra details about the request"></textarea>
                        </div> 
                        <div class="table-responsive">
                            <table class="table table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>Project Permission</th>
                                        <th class="text-center">Read</th>
                                        <th class="text-center">Write</th>
                                        <th class="text-center">Create</th>
                                        <th class="text-center">Delete</th>
                                        <th class="text-center">Import</th>
                                        <th class="text-center">Export</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Projects</td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1" checked>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2" checked>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3" checked>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault4" checked>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault5" checked>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault6" checked>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="fw-bold">Tasks</td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault7" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault8" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault9" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault10" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault11" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault12" checked>
                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="fw-bold">Chat</td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault13" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault14" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault15" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault16" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault17" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault18" checked>
                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="fw-bold">Estimates</td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault19" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault20" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault21" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault22" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault23" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault24" checked>
                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="fw-bold">Invoices</td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault25" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault26">
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault27" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault28">
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault29" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault30" checked>
                        
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="fw-bold">Timing Sheets</td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault31" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault32" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault33" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault34" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault35" checked>
                        
                                        </td>
                                        <td class="text-center">
                        
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault36" checked>
                        
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Done</button>
                        <button type="button" class="btn btn-primary">Create</button>
                    </div> 
                </div>  
            </div>
        </div>
@endsection
@push('plugins-js')
<script src="{{asset('assets/bundles/dataTables.bundle.js')}}"></script>
@endpush
@push('js')
<script>
let AppDepartmentListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#membres");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppDepartmentListManager.init();
});

</script>

<!-- <script>
    document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll("#membres tbody tr");

    rows.forEach((row) => {
        row.addEventListener("click", (e) => {
            // Vérifie si l'utilisateur a cliqué sur un bouton ou un élément interactif
            if (
                e.target.closest(".btn") || // Boutons d'action
                e.target.closest(".btn-group") // Conteneur des boutons
            ) {
                return; // Ne rien faire si le clic est sur un bouton
            }

            // Récupère l'URL de redirection depuis l'attribut data-href
            const href = row.dataset.href;
            if (href) {
                window.location.href = href; // Redirige l'utilisateur vers la page de détails
            }
        });
    });
});
</script> -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const table = $("#membres").DataTable(); // Initialisation de DataTables

        // Ajout de la gestion des clics via la délégation d'événements
        $('#membres tbody').on('click', 'tr', function (e) {
            // Vérifie si l'utilisateur a cliqué sur un bouton ou un élément interactif
            if (
                e.target.closest(".btn") || // Boutons d'action
                e.target.closest(".btn-group") // Conteneur des boutons
            ) {
                return; // Ne rien faire si le clic est sur un bouton
            }

            // Récupère l'URL de redirection depuis l'attribut data-href
            const href = this.dataset.href;
            if (href) {
                window.location.href = href; // Redirige l'utilisateur vers la page de détails
            }
        });
    });
</script>
@endpush