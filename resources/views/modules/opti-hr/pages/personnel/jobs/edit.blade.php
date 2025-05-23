<!-- Modal update-->
<div class="modal fade" id="updateJobModal{{ $job->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-job">Modifier Poste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modelUpdateFormContainer" id="updateJobForm{{ $job->id }}">
               
                <form data-model-update-url="{{ route('jobs.update', $job->id) }}">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="mb-3">
                        <label for="title" class="form-label required">Titre</label>
                        <input value='{{$job->title}}' type="text" class="form-control" id="title" name="title" {{ $job->title === 'DG' ? 'disabled' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label required">Description</label>
                        <input type="text" value='{{$job->description}}' class="form-control" id="description" name="description" {{ $job->title === 'DG' ? 'disabled' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label for="n" class="form-label required">N+1</label>
                        <select class="form-select" aria-label="Default select example" name="n_plus_one_job_id" value='{{$job->n_plus_one_job->id ?? ""}}'>
                            @if($job->n_plus_one_job != null && $job->n_plus_one_job->title != 'DG')
                            @foreach ($department->jobs as $jobOption)
                                <option value="{{ $jobOption->id }}">{{ $jobOption->title }}</option>
                            @endforeach
                            @else 
                            <option value="{{ $job->n_plus_one_job->id ?? 'N/A' }}">{{ $job->n_plus_one_job->title ?? 'N/A' }}</option>
                            @endif
                        </select>
                    </div>
                    <input type="hidden" name="department_id" value="{{ $department->id }}">
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Modifier Job"
                        data-bs-dismiss="modal">
                        <span class="normal-status">
                            Modifier
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
