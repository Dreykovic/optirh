 <div class="modal fade" id="addDeptModal" tabindex="-1"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  fw-bold" id="depaddLabel"> Nouvelle Direction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modelAddForm" data-model-add-url="{{ route('directions.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label required">Sigle</label>
                            <input type="text" class="form-control" id="name" name='name' >
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label required">Définition</label>
                            <input type="text" class="form-control" id="description" name='description'>
                        </div>
                      
                        <div class="mb-3">
                            <label for="head" class="form-label">Directeur</label>
                            <select class="form-select" aria-label="Default select example" name='director_id'>
                                <option selected disabled>Choisir le directeur</option> <!-- Option par défaut -->
                                @if($employees)
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">
                                            {{ $emp->last_name }} {{ $emp->first_name }} 
                                            @if($emp->title)
                                                - <i class='' style="color: #6c757d;">{{ $emp->title }}</i>
                                            @else
                                                - Pas de poste attribué
                                            @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary" atl="Ajouter Direction" id="modelAddBtn"
                                data-bs-dismiss="modal">
                                <span class="normal-status">
                                    Ajouter
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