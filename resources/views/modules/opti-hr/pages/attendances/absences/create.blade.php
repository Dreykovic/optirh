@extends('modules.opti-hr.pages.base')

@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/absences.css') }}">
@endsection

@section('admin-content')
    @php
        $currentDuty = auth()->user()->getCurrentDuty();
        $absenceBalance = $currentDuty ? ($currentDuty->absence_balance ?? 30) : 30;
        $maxBalance = 30; // Solde maximum possible
    @endphp

    {{-- Données pour le JavaScript --}}
    <div id="absenceFormData" class="d-none"
         data-absence-balance="{{ $absenceBalance }}"
         data-max-balance="{{ $maxBalance }}"
         data-holidays="{{ json_encode($holidays ?? []) }}">
    </div>

    <div class="absence-form-container">
        <div class="absence-form-card">
            {{-- Header --}}
            <div class="absence-form-header">
                <h1><i class="bi bi-calendar-plus me-2"></i>Nouvelle Demande d'Absence</h1>
                <p>Remplissez le formulaire ci-dessous pour soumettre votre demande</p>
                <i class="bi bi-calendar2-week header-icon"></i>
            </div>

            {{-- Form Body --}}
            <div class="absence-form-body">
                <form id="modelAddForm" data-model-add-url="{{ route('absences.save') }}" novalidate>
                    @csrf

                    {{-- Section 1: Type d'absence --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon type">
                                <i class="bi bi-tag"></i>
                            </div>
                            <div>
                                <h2 class="section-title">Type d'absence</h2>
                                <p class="section-subtitle">Sélectionnez le motif de votre demande</p>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label" for="absenceTypeSelect">
                                Type d'absence <span class="required">*</span>
                            </label>
                            <select class="form-select" id="absenceTypeSelect" name="absence_type" required>
                                <option value="">Sélectionnez un type d'absence</option>
                                @foreach ($absenceTypes as $absenceType)
                                    <option value="{{ $absenceType->id }}"
                                            data-deductible="{{ $absenceType->is_deductible ? 'true' : 'false' }}">
                                        {{ $absenceType->label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un type d'absence.</div>
                        </div>

                        {{-- Info déductibilité --}}
                        <div id="deductibilityInfo" class="deductibility-info d-none">
                            <i class="bi bi-info-circle"></i>
                            <span id="deductibilityText"></span>
                        </div>

                        {{-- Indicateur de solde --}}
                        <div id="balanceCard" class="balance-card d-none">
                            <div class="balance-header">
                                <span class="balance-label">
                                    <i class="bi bi-wallet2 me-1"></i> Votre solde de congés
                                </span>
                                <span class="balance-value">
                                    <span id="balanceDisplay">{{ $absenceBalance }}</span>/{{ $maxBalance }} jours
                                </span>
                            </div>
                            <div class="balance-progress">
                                <div class="balance-progress-bar" id="balanceProgressBar"
                                     style="width: {{ ($absenceBalance / $maxBalance) * 100 }}%"></div>
                            </div>
                            <div class="balance-info">
                                <span>Solde disponible</span>
                                <span id="balanceAfterRequest"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Période --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon dates">
                                <i class="bi bi-calendar-range"></i>
                            </div>
                            <div>
                                <h2 class="section-title">Période d'absence</h2>
                                <p class="section-subtitle">Définissez les dates de votre absence</p>
                            </div>
                        </div>

                        <div class="dates-grid">
                            <div>
                                <label for="absenceStartDate" class="form-label">
                                    Date de début <span class="required">*</span>
                                </label>
                                <input type="date"
                                       class="form-control"
                                       id="absenceStartDate"
                                       name="start_date"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                <div class="invalid-feedback">Veuillez sélectionner une date de début.</div>
                            </div>
                            <div>
                                <label for="absenceEndDate" class="form-label">
                                    Date de fin <span class="required">*</span>
                                </label>
                                <input type="date"
                                       class="form-control"
                                       id="absenceEndDate"
                                       name="end_date"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                <div class="invalid-feedback">Veuillez sélectionner une date de fin.</div>
                            </div>
                        </div>

                        {{-- Badge durée --}}
                        <div id="durationBadge" class="duration-badge d-none">
                            <i class="bi bi-clock-history"></i>
                            <span id="durationText">0 jour ouvré</span>
                        </div>
                    </div>

                    {{-- Section 3: Adresse --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon address">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <h2 class="section-title">Adresse pendant l'absence</h2>
                                <p class="section-subtitle">Lieu où vous serez joignable</p>
                            </div>
                        </div>

                        <div>
                            <label for="absenceAddress" class="form-label">
                                Adresse <span class="required">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="absenceAddress"
                                   name="address"
                                   value="{{ auth()->user()->employee?->address1 ?? '' }}"
                                   placeholder="Ex: 123 rue Example, Ville"
                                   required>
                            <p class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Cette adresse sera utilisée en cas de besoin pendant votre absence.
                            </p>
                            <div class="invalid-feedback">Veuillez indiquer votre adresse.</div>
                        </div>
                    </div>

                    {{-- Section 4: Motif --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon reason">
                                <i class="bi bi-chat-text"></i>
                            </div>
                            <div>
                                <h2 class="section-title">Motif de la demande</h2>
                                <p class="section-subtitle">Expliquez brièvement la raison (optionnel)</p>
                            </div>
                        </div>

                        <div>
                            <label for="absenceReason" class="form-label">Motif</label>
                            <textarea class="form-control"
                                      id="absenceReason"
                                      rows="3"
                                      name="reasons"
                                      placeholder="Ex: Vacances familiales, rendez-vous médical, etc."
                                      maxlength="1000"></textarea>
                            <p class="form-text">
                                <span id="charCount">0</span>/1000 caractères
                            </p>
                        </div>
                    </div>

                    {{-- Section 5: Justificatif --}}
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon proof">
                                <i class="bi bi-paperclip"></i>
                            </div>
                            <div>
                                <h2 class="section-title">Justificatif</h2>
                                <p class="section-subtitle">Joignez un document si nécessaire (optionnel)</p>
                            </div>
                        </div>

                        <div class="upload-zone" id="uploadZone">
                            <input type="file"
                                   name="proof"
                                   id="proofInput"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <p class="upload-text">Glissez un fichier ou cliquez pour sélectionner</p>
                            <p class="upload-hint">PDF, JPG, PNG - Maximum 5 Mo</p>
                        </div>

                        <div class="file-preview-container" id="filePreviewContainer"></div>
                    </div>

                    {{-- Résumé de la demande --}}
                    <div class="summary-card d-none" id="summaryCard">
                        <div class="summary-header">
                            <i class="bi bi-clipboard-check"></i>
                            <h3>Résumé de votre demande</h3>
                        </div>
                        <div class="summary-body">
                            <div class="summary-row">
                                <span class="summary-label">Type d'absence</span>
                                <span class="summary-value" id="summaryType">-</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Période</span>
                                <span class="summary-value" id="summaryPeriod">-</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Durée</span>
                                <span class="summary-value highlight" id="summaryDuration">-</span>
                            </div>
                            <div class="summary-row" id="summaryBalanceRow">
                                <span class="summary-label">Solde après demande</span>
                                <span class="summary-value" id="summaryBalance">-</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Adresse</span>
                                <span class="summary-value" id="summaryAddress">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="window.history.back()">
                            <i class="bi bi-x-lg me-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn-submit" id="modelAddBtn" disabled>
                            <span class="normal-status">
                                <i class="bi bi-check2-circle"></i> Soumettre la demande
                            </span>
                            <span class="indicateur d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Envoi en cours...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('plugins-js')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
@endpush

@push('js')
    <script src="{{ asset('app-js/attendances/absences/create.js') }}"></script>
@endpush
