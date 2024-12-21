<div class="modal fade" id="updateDeptModal{{ $department->id }}" tabindex="-1"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  fw-bold" id="depaddLabel"> Nouvelle Direction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modelUpdateFormContainer" id="updateDeptForm{{ $department->id }}">
                    <form data-model-update-url="{{ route('directions.update', $department->id) }}">
                        {{--@csrf--}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Sigle</label>
                            <input type="text" value="{{ $department->name }}" class="form-control" id="name" name="name" {{ $department->name === 'DG' ? 'disabled' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Définition</label>
                            <input type="text" value="{{ $department->description }}" class="form-control" id="description" name='description' {{ $department->name === 'DG' ? 'disabled' : '' }}>
                        </div>
                      
                        <div class="mb-3">
                            <label for="head" class="form-label">Directeur</label>
                            <input type="text" value="{{ $department->director ? $department->director->first_name . ' ' . $department->director->last_name : 'Non assigné' }}" class="form-control" id="head" name="director_id" disabled>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Modifier Dept"
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