<div class="modal fade" id="createemp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title  fw-bold" id="createprojectlLabel"> Ajouter un employé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="deadline-form">
                    <form id="modelAddForm" data-model-add-url="{{ route('employees.save') }}"
                        enctype="multipart/form-data">

                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="username" class="form-label">User Name</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Nom d'utilisateur">
                            </div>
                            <div class="col">
                                <label for="email" class="form-label">Email ID</label>
                                <input type="email" class="form-control" id="email" placeholder="Adresse mail"
                                    name="email">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">

                            <div class="col">
                                <label for="pwd" class="form-label">Password</label>
                                <input type="Password" class="form-control" id="pwd" placeholder="Password"
                                    name="password">
                            </div>

                            <div class="col">
                                <label for="pwd" class="form-label">Password Confirmation</label>
                                <input type="password" class="form-control" id="pwd"
                                    placeholder="Password confirmation" name="password_confirmation">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label class="form-label">Roles</label>
                                <select class="form-select" aria-label="Default select Project Category" name="role">
                                    <option value=""></option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach

                                </select>
                            </div>

                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo de Profile</label>
                            <input class="form-control" type="file" id="photo" name="photo">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" atl="addTransaction" id="modelAddBtn"
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
</div>
