<!-- Modal pour l'ajout de credentials utilisateur -->
<div class="modal fade" id="credentialAddModal" tabindex="-1" aria-labelledby="credentialAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form id="modelAddForm" class="modal-content shadow-lg" data-model-add-url="{{ route('credentials.save') }}">
            @csrf
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-primary" id="credentialAddModalLabel">
                    <i class="fas fa-user-lock me-2"></i>Créer Accès Utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle me-2 fs-5"></i>
                    <div>
                        Le nom d'utilisateur du membre du personnel sera généré automatiquement et envoyé par e-mail.
                        Celui-ci devra modifier son mot de passe lors de sa première connexion.
                    </div>
                </div>

                <!-- Sélection d'employé -->
                <div class="mb-4">
                    <label class="form-label fw-semibold required" for="employeeId">Sélectionner un employé</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="fas fa-user-tie"></i>
                        </span>
                        <select class="form-select form-select-solid" id="employeeId" name="employee" required>
                            <option value="" selected disabled>Choisir un employé...</option>
                            @foreach ($employeesWithoutUser as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->last_name . ' ' . $employee->first_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Rôle utilisateur -->
                <div class="mb-4">
                    <label class="form-label fw-semibold required d-block">Rôle</label>
                    <div class="card border shadow-sm">
                        <div class="card-body p-3">
                            @foreach ($roles as $role)
                                <div class="form-check custom-option custom-option-basic mb-3">
                                    <input class="form-check-input" type="radio" name="role"
                                        value="{{ $role->name }}" id="role_option_{{ $role->id }}"
                                        {{ $loop->first ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex justify-content-between"
                                        for="role_option_{{ $role->id }}">
                                        <span class="fw-semibold text-gray-800">{{ $role->name }}</span>
                                    </label>
                                </div>
                                @if (!$loop->last)
                                    <div class="separator separator-dashed my-2"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @can('voir-un-tout')
                    <!-- Permissions d'accès -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold required d-block">Type d'accès au système</label>
                        <div class="card border shadow-sm">
                            <div class="card-body p-3">
                                <!-- Accès Recours -->
                                <div class="form-check custom-option custom-option-basic mb-3">
                                    <input class="form-check-input" type="radio" name="permission" value="access-recours"
                                        id="permission_recours">
                                    <label class="form-check-label d-flex align-items-center" for="permission_recours">
                                        <span class="me-2"><i class="fas fa-balance-scale text-primary"></i></span>
                                        <span class="fw-semibold">Accès Recours</span>
                                    </label>
                                </div>
                                <div class="separator separator-dashed my-2"></div>

                                <!-- Accès Opti-HR -->
                                <div class="form-check custom-option custom-option-basic mb-3">
                                    <input class="form-check-input" type="radio" name="permission" value="access-opti-hr"
                                        id="permission_opti_hr">
                                    <label class="form-check-label d-flex align-items-center" for="permission_opti_hr">
                                        <span class="me-2"><i class="fas fa-users text-success"></i></span>
                                        <span class="fw-semibold">Accès Opti-HR</span>
                                    </label>
                                </div>
                                <div class="separator separator-dashed my-2"></div>

                                <!-- Accès Complet -->
                                <div class="form-check custom-option custom-option-basic">
                                    <input class="form-check-input" type="radio" name="permission" value="access-all"
                                        id="permission_all">
                                    <label class="form-check-label d-flex align-items-center" for="permission_all">
                                        <span class="me-2"><i class="fas fa-key text-danger"></i></span>
                                        <span class="fw-semibold">Accès Complet</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="submit" class="btn btn-primary" id="modelAddBtn">
                    <span class="normal-status">
                        <i class="fas fa-save me-1"></i>Ajouter
                    </span>
                    <span class="indicateur d-none">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Traitement en cours...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Styles supplémentaires pour améliorer l'apparence -->
<style>
    /* Styles pour les options personnalisées */
    .custom-option {
        padding: 1rem;
        border: 1px solid #e4e6ef;
        border-radius: 0.475rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .custom-option:hover {
        background-color: #f8f9fa;
    }

    .custom-option-basic input:checked~label {
        border-color: #3699ff;
        background-color: #f1faff;
    }

    /* Animation pour le spinner */
    .spinner-grow {
        animation-duration: 0.75s;
    }

    /* Styles pour les champs obligatoires */
    .required:after {
        content: " *";
        color: #f1416c;
    }
</style>
