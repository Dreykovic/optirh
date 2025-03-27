<!-- Modal Create-->
<div class="modal fade" id="createJobModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau Poste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modelAddForm" data-model-add-url="{{ route('jobs.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label required">Titre</label>
                        <input type="text" class="form-control" id="title" name='title'>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label required">Description</label>
                        <input type="text" class="form-control" id="description" name='description'>
                    </div>
                    <div class="mb-3">
                        <label for="n" class="form-label required">N+1</label>
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
