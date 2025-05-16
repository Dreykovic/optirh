@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        .form-text {
            font-size: 0.875em;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .required:after {
            content: " *";
            color: red;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 1.25rem 1.5rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        .card-header h3 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .days-requested-container {
            background-color: #f8f9fa;
            padding: 0.75rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .days-requested {
            font-weight: 600;
            color: #0d6efd;
        }
    </style>
@endsection

@section('admin-content')
    <!-- Si disponible, stocker le solde d'absence actuel dans un élément caché -->
    @if(auth()->user()->employee->duties->where('evolution', 'ON_GOING')->first())
        <div id="currentDuty" class="d-none" 
             data-absence-balance="{{ auth()->user()->employee->duties->where('evolution', 'ON_GOING')->first()->absence_balance ?? 30 }}">
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12">
            <div class="card p-xl-5 p-lg-4 p-0">
                <div class="deadline-form">
                    <div class="card-header">
                        <h3>Soumettre Une Demande d'Absence</h3>
                    </div>
                    <form id="modelAddForm" data-model-add-url="{{ route('absences.save') }}">
                        @csrf
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label required" for="absenceTypeSelect">Type d'absence</label>
                                <select class="form-select" id="absenceTypeSelect" name="absence_type">
                                    <option value="">Sélectionnez un type d'absence</option>
                                    @foreach ($absenceTypes as $absenceType)
                                        <option value="{{ $absenceType->id }}" 
                                                data-deductible="{{ $absenceType->is_deductible ? 'true' : 'false' }}">
                                            {{ $absenceType->label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Veuillez sélectionner le type d'absence que vous souhaitez demander.</div>
                                
                                <!-- Statut de déductibilité -->
                                <div id="deductibleStatus" style="display: none;">
                                    <span id="deductibleText"></span>
                                </div>
                                
                                <!-- Information sur le solde d'absence -->
                                <div id="absenceBalanceInfo" style="display: none;" class="mt-2">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Votre solde de congés actuel: <span id="currentBalance" class="fw-bold">30</span> jours
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="absenceAddress" class="form-label required">Adresse De Congé</label>
                                <input type="text" class="form-control" id="absenceAddress" name="address"
                                    value="{{ auth()->user()->employee->address1 }}"
                                    placeholder="Votre adresse pendant l'absence">
                                <div class="form-text">Adresse où vous serez joignable pendant votre absence.</div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="absenceStartDate" class="form-label required">Date de début</label>
                                    <input type="date" class="form-control" id="absenceStartDate" name="start_date">
                                    <div class="form-text">Premier jour de votre absence.</div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="absenceEndDate" class="form-label required">Date de fin</label>
                                    <input type="date" class="form-control" id="absenceEndDate" name="end_date">
                                    <div class="form-text">Dernier jour de votre absence.</div>
                                </div>
                            </div>

                            <div class="days-requested-container mb-3">
                                <i class="bi bi-calendar-check me-2"></i> Durée totale: <strong><span
                                        class="days-requested">0 jour</span></strong>
                                <div class="form-text mt-1">
                                    Calculé automatiquement en jours ouvrés (hors week-ends, jours fériés inclus).
                                    <i class="bi bi-question-circle tooltip-icon" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Le nombre de jours est calculé en incluant les jours de début et de fin."></i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="absenceReason" class="form-label">Raisons de l'absence</label>
                                <textarea class="form-control" id="absenceReason" rows="3" name="reasons"
                                    placeholder="Veuillez expliquer brièvement les raisons de votre demande d'absence"></textarea>
                                <div class="form-text">Ces informations seront partagées avec votre responsable.</div>
                            </div>

                        </div>
                        <div class="card-footer modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="bi bi-x-circle me-1"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-primary" atl="Ajouter Absence Requête" id="modelAddBtn">
                                <span class="normal-status">
                                    <i class="bi bi-check-circle me-1"></i> Soumettre
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
    </div> <!-- Row end  -->
    
    <!-- Modal de confirmation -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation de la demande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Le contenu sera injecté dynamiquement par JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmit">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('plugins-js')
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script>
    <!-- Bootstrap JS pour les modales et tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr pour les notifications élégantes si disponible -->
    @if(config('app.env') === 'local')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endif
@endpush
@push('js')
    <script src="{{ asset('app-js/attendances/absences/create.js') }}"></script>
@endpush