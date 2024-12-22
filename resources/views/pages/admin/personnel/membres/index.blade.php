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
                                <button type="button" class="btn btn-dark me-1 mt-1 w-sm-100" data-bs-toggle="modal" data-bs-target="#addEmpModal"><i class="icofont-plus-circle me-2 fs-6"></i>Ajouter</button>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle mt-1  w-sm-100" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                        Direction
                                    </button>
                                    <ul class="dropdown-menu  dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="dropdown-item" href="#">Tout</a></li>
                        
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
                                                    
                                                    @if ($employee)
                                                    <i class="icofont icofont-{{ $employee->gender === 'FEMALE' ? 'businesswoman' : 'business-man-alt-2' }} fs-3  avatar rounded-circle"></i>
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
                                                 <!--  -->
                                                 <div class="btn-group">
                                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <span class='btn' data-bs-toggle="modal" data-bs-target=""><i class="icofont-edit text-success m-2"></i>Editer</span>
                                                            </li>
                                                            <li>
                                                                <span class='btn'><i class="icofont-ui-delete text-danger m-2"></i></i>Supprimer</span>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('membres.show', $employee->id) }}">
                                                                    <span class='btn'><i class="icofont-eye-open m-2"></i></i>Détail</span>
                                                                        
                                                                </a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </div><!-- /btn-group -->                                         
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

      @include('pages.admin.personnel.membres.create')
@endsection
@push('plugins-js')
<script src="{{asset('assets/bundles/dataTables.bundle.js')}}"></script>
@endpush
@push('js')
<script src="{{ asset('app-js/crud/post.js') }}"></script>

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

<script>
    function loadJobs(departmentId) {
        // Vérifie si un département a été sélectionné
        if (!departmentId) {
            document.getElementById('job').innerHTML = '<option selected>choisir</option>';
            return;
        }

        // Effectue une requête AJAX
        fetch(`/api/jobs/${departmentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erreur lors du chargement des jobs");
                }
                return response.json();
            })
            .then(data => {
                // Met à jour le champ "Poste"
               
                const jobSelect = document.getElementById('job');
                jobSelect.innerHTML = '<option selected>choisir</option>';
                data.forEach(job => {
                    jobSelect.innerHTML += `<option value="${job.id}">${job.title}</option>`;
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert("Impossible de charger les postes....");
            });
    }
</script>

@endpush