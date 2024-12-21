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
                                                <span class="fw-bold">{{$nbreduty}}</span>
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
                   
                </div>
            </div>
        </div>
        <div class="row clearfix g-3">
            <div class="col-sm-12">
                <div class='d-flex justify-content-between'>
                    <h5>Liste des postes</h5>
                    <button type="button" class="btn btn-outline-primary mb-2 d-flex justify-content-between" data-bs-toggle="modal" data-bs-target="#createJobModal"><i class="icofont-plus fs-4 text-success"></i><span>Nouveau Poste</span> </button>
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
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#job_employees" data-bs-job-id='{{$job->id}}'><i class="text-success">Voir</i></button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-secondary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#updateJobModal{{ $job->id }}"
                                        {{ (strtolower($job->title) === 'dg' || preg_match('/^directeur(·rice)?/i', $job->title)) ? 'disabled' : '' }}
                                    >
                                        <i class="icofont-edit text-success"></i>
                                    </button>

                                    <!--  -->
                                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary deleterow" {{ (strtolower($job->title) === 'dg' || preg_match('/^directeur(·rice)?/i', $job->title)) ? 'disabled' : '' }}>
                                            <i class="icofont-ui-delete text-danger"></i>
                                        </button>
                                    </form>

                                   
                                </div>
                            </td>
                            @include('pages.admin.personnel.jobs.edit')

                        </tr>
                    @endforeach    
                    </tbody>
                </table> 
                </div>
                </div>
            </div>
        </div>
<!-- Modal employees-->
<div class="modal fade" id="job_employees" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="job_employeesLabel">Employés Assignés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="employee-list" class="list-group">
                    <!-- Les employés seront injectés ici -->
                </ul>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
        </div>
    </div>
</div>
@include('pages.admin.personnel.jobs.create')

@endsection
@push('plugins-js')
<script src="{{asset('assets/bundles/dataTables.bundle.js')}}"></script>

@endpush
@push('js')
<script src="{{ asset('app-js/crud/post.js') }}"></script>
<script src="{{ asset('app-js/crud/put.js') }}"></script>
<script src="{{ asset('app-js/crud/delete.js') }}"></script>
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


@endpush