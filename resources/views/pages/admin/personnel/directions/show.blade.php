@extends('pages.admin.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
@endsection
@section('admin-content')
    <!-- Body: Body -->       
    <div class="body d-flex py-lg-3 py-md-2">
            <div class="container-xxl">
                <div class="row align-items-center">
                    <div class="border-0 mb-4">
                        <div class="card-header py-3 no-bg bg-transparent d-flex align-items-center px-0 justify-content-between border-bottom flex-wrap">
                            <!-- <h3 class="fw-bold mb-0">{{$department->name}}({{$department->description}})</h3> -->
                            <h3 class="fw-bold mb-0">
                                {{$department->name}}
                                <span data-bs-toggle="tooltip" title="{{ $department->description }}">
                                    <i class="icofont-question-circle" style="cursor: pointer;"></i>
                                </span>
                            </h3>

                        </div>
                    </div>
                </div> <!-- Row end  -->
                <div class="row g-3">
                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                            <div class="card ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail bg-lightblue color-defult"><i class="icofont-user fs-4"></i></div>
                                            <div class="flex-fill ms-4 text-truncate">
                                                @if ($department->director)
                                                <span class="fw-bold ms-1">{{ $department->director->first_name }} {{ $department->director->last_name }}</span>
                                                @else
                                                <span class="text-muted">Aucun directeur assigné</span>
                                                @endif
                                                <div class="text-truncate">Directeur.trice</div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail bg-lightblue color-defult"><i class="icofont-users-social fs-4"></i></div>
                                            <div class="flex-fill ms-4 text-truncate">
                                                <span class="fw-bold">0</span>
                                                <div class="text-truncate">Collaborateurs</div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card ">
                                <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar lg  rounded-1 no-thumbnail bg-lightblue color-defult"><i class="icofont-chart-flow-1 fs-4"></i></div>
                                            <div class="flex-fill ms-4 text-truncate">
                                                <span class="fw-bold">{{$nbre_postes}}</span>
                                                <div class="text-truncate">Postes</div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- <div class="col-xxl-2 col-xl-2 col-lg-12 col-md-12">
                        <div class="card ">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                    <div class="avatar lg  rounded-1 no-thumbnail bg-lightblue color-defult"><i class="icofont-plus fs-4"></i></div>
                                    <div class="flex-fill ms-4 text-truncate">
                                        <div class="fw-bold">Poste</div>
                                    </div>
                                    
                                </div>
                                </div>
                             </div>
                    </div> -->
                    <!-- <div class="col-md-2">
                        <div class="card ">
                        <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar lg  rounded-1 no-thumbnail bg-lightblue color-defult"><i class="icofont-plus fs-4"></i></div>
                                    <div class="flex-fill ms-4 text-truncate">
                                        <div class="fw-bold">Poste</div>
                                    </div>
                                    
                                </div>
                            </div>
                            </div>
                            </div> -->
                    <!--  -->
                </div>
            </div>
        </div>
        <div class="row clearfix g-3">
            <div class="col-sm-12">
                <div class='d-flex justify-content-between'>
                    <h5>Liste des postes</h5>
                    <button type="button" class="btn btn-outline-primary mb-2 d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#add-job"><i class="icofont-plus fs-4 text-success"></i><span>Nouveau Poste</span> </button>
                </div>
                <div class="card mb-3">
                     <div class="card-body">
                 <table id="postes" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <!-- <th>#</th> -->
                            <th>Titre</th> 
                            <th>Description</th> 
                            <th>N+1</th>   
                            <th>Employés</th>  
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($department->jobs as $index => $job)
                        <tr>
                            <!-- <td>
                                <span class="fw-bold">{{ $index + 1 }}</span>
                            </td> -->
                            <td>
                                <span class="fw-bold ms-1">{{$job->title}}</span>
                            </td>
                            <td class='text-wrap w-50'>
                                {{$job->description}}
                            </td>

                            @if($job->n_plus_one_job)
                            <td>
                                {{$job->n_plus_one_job->title}}
                            </td>    
                            @else
                              <td>Pas de N+1</td>
                            @endif

                            

                            <td>
                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                    <!-- <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#depedit"><i class="text-success">Voir</i></button> -->
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#job_employees"><i class="text-success">Voir</i></button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                    <!-- <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#update-job">
                                        <i class="icofont-edit text-success"></i>
                                    </button> -->
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-secondary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#updateJobModal{{ $job->id }}"                                    >
                                        <i class="icofont-edit text-success"></i>
                                    </button>

                                    <!--  -->
                                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary deleterow">
                                            <i class="icofont-ui-delete text-danger"></i>
                                        </button>
                                    </form>

                                   @include('pages.admin.personnel.jobs.edit')
                                </div>
                            </td>
                        </tr>
                    @endforeach    
                    </tbody>
                </table> 
                </div>
                </div>
            </div>
        </div>
<!-- Modal -->
<div class="modal fade" id="job_employees" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="job_employeesLabel">Employés Assignés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Woohoo, you're reading this text in a modal!</p>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
        </div>
    </div>
</div>
<!-- Modal Create-->
<div class="modal fade" id="add-job" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-job">Nouveau Poste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modelAddForm" data-model-add-url="{{ route('jobs.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="title" name='title'>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name='description'>
                    </div>
                    <div class="mb-3">
                        <label for="n" class="form-label">N+1</label>
                        <select class="form-select" aria-label="Default select example" name='n_plus_one_job_id'>
                            @if($department->jobs)
                                @foreach ($department->jobs as $job)

                                <option value="{{$job->id}}">{{$job->title}}</option>
                                @endforeach
                            @else
                                <option value="1">N/A</option>
                            @endif
                        </select>
                    </div>
                    <input type="text" class="form-control" name='department_id' value='{{$department->id}}' hidden>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-lg btn-block lift text-uppercase btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" data-bs-dismiss="modal" class="btn btn-lg btn-block lift text-uppercase btn-primary" atl="uu"
                            id="modelAddBtn">
                            <span class="normal-status">
                                Enregistrer
                            </span>
                            <span class="indicateur d-none">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Un Instant...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            <!--  -->
        </div>
    </div>
</div>

@endsection
@push('plugins-js')
<script src="{{asset('assets/bundles/dataTables.bundle.js')}}"></script>

@endpush
@push('js')
<script src={{ asset('app-js/crud/post.js') }}></script>
<script src={{ asset('app-js/crud/put.js') }}></script>
<script>
    // Assurez-vous que le DOM est complètement chargé
    document.addEventListener('DOMContentLoaded', function () {
        // Initialiser tous les tooltips
        var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
<script>
let AppPostesListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#postes");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppPostesListManager.init();
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Cible le modal de mise à jour
    const updateJobModal = document.getElementById('update-job');
    
    // Écoute l'événement d'ouverture du modal
    updateJobModal.addEventListener('show.bs.modal', function (event) {
        // Bouton déclencheur
        const button = event.relatedTarget;

        // Récupère les données depuis les attributs `data-*`
        const jobId = button.getAttribute('data-job-id');
        const jobTitle = button.getAttribute('data-job-title');
        const jobDescription = button.getAttribute('data-job-description');
        const jobNPlusOne = button.getAttribute('data-job-n-plus-one');

        // Sélectionne les champs du formulaire dans le modal
        const modalTitleField = updateJobModal.querySelector('input[name="title"]');
        const modalDescriptionField = updateJobModal.querySelector('input[name="description"]');
        const modalNPlusOneField = updateJobModal.querySelector('select[name="n_plus_one_job_id"]');
        const modalForm = updateJobModal.querySelector('form');

        // Remplit les champs avec les données du job
        modalTitleField.value = jobTitle;
        modalDescriptionField.value = jobDescription;
        modalNPlusOneField.value = jobNPlusOne;

        // Met à jour l'action du formulaire avec l'ID du job
        // modalForm.action = `/jobs/${jobId}`;
        // modalForm.method = 'POST';
        // modalForm.data-model-update-url = `/jobs/${jobId}`;
        const updateUrl = `/jobs/${jobId}`;
        modalForm.setAttribute('data-model-update-url', updateUrl);


        // Ajoute le champ `_method` pour la requête PUT (nécessaire pour Laravel)
        let methodInput = modalForm.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.setAttribute('type', 'hidden');
            methodInput.setAttribute('name', '_method');
            modalForm.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    });
});

</script>
@endpush
