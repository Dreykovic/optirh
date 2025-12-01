@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet">
    <style>
        .document-type-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
        }
        .document-type-info.exceptional {
            border-left-color: #ffc107;
            background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
        }
        .form-section {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .form-section-title {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        #dateRangeFields {
            transition: all 0.3s ease;
        }
        .help-icon {
            cursor: help;
            color: #6c757d;
        }
    </style>
@endsection

@section('admin-content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12">
            {{-- En-tête avec retour --}}
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('documents.requests') }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="icofont-arrow-left"></i> Retour
                </a>
                <div>
                    <h3 class="mb-0">Nouvelle Demande de Document</h3>
                    <small class="text-muted">Sélectionnez le type et remplissez les informations</small>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="deadline-form">
                    <form id="modelAddForm" data-model-add-url="{{ route('documents.save') }}">
                        @csrf
                        <div class="card-body p-4">
                            {{-- Section 1: Type de document --}}
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="icofont-file-document me-2"></i>Type de Document
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold" for="documentTypeSelect">
                                        Sélectionnez le type de document souhaité
                                        <i class="icofont-question-circle help-icon ms-1"
                                           data-bs-toggle="tooltip"
                                           title="Le type détermine la période couverte par le document"></i>
                                    </label>
                                    <select class="form-select form-select-lg" id="documentTypeSelect" name="document_type">
                                        <option value="" selected disabled>-- Choisir un type --</option>
                                        @foreach ($documentTypes as $documentType)
                                            <option value="{{ $documentType->id }}"
                                                    data-type="{{ $documentType->type }}"
                                                    data-description="{{ $documentType->description }}">
                                                {{ $documentType->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Info box du type sélectionné --}}
                                <div id="documentTypeInfo" class="document-type-info p-3 rounded d-none">
                                    <div class="d-flex align-items-start">
                                        <i class="icofont-info-circle fs-4 me-3 text-primary" id="typeInfoIcon"></i>
                                        <div>
                                            <strong id="typeInfoTitle">Information</strong>
                                            <p class="mb-0 mt-1 text-muted small" id="typeInfoDescription"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Section 2: Période (conditionnelle) --}}
                            <div class="form-section" id="dateRangeSection">
                                <div class="form-section-title">
                                    <i class="icofont-calendar me-2"></i>Période du Document
                                </div>

                                <div class="row g-3" id="dateRangeFields">
                                    <div class="col-sm-6">
                                        <label for="documentStartDate" class="form-label fw-bold">
                                            <i class="icofont-ui-calendar me-1 text-success"></i>Date de début
                                        </label>
                                        <input type="date" class="form-control form-control-lg"
                                               id="documentStartDate" name="start_date">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="documentEndDate" class="form-label fw-bold">
                                            <i class="icofont-ui-calendar me-1 text-danger"></i>Date de fin
                                        </label>
                                        <input type="date" class="form-control form-control-lg"
                                               id="documentEndDate" name="end_date">
                                    </div>
                                </div>

                                {{-- Message pour type EXCEPTIONAL --}}
                                <div id="exceptionalMessage" class="alert alert-warning mt-3 d-none">
                                    <i class="icofont-warning me-2"></i>
                                    <strong>Dates automatiques</strong><br>
                                    <small>Pour ce type de document, les dates seront calculées automatiquement
                                    en fonction de votre période d'emploi.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="card-footer bg-light d-flex justify-content-between py-3">
                            <a href="{{ route('documents.requests') }}" class="btn btn-outline-secondary">
                                <i class="icofont-close me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4" id="modelAddBtn">
                                <span class="normal-status">
                                    <i class="icofont-paper-plane me-2"></i>Soumettre la demande
                                </span>
                                <span class="indicateur d-none">
                                    <span class="spinner-grow spinner-grow-sm" role="status"></span>
                                    Envoi en cours...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Aide contextuelle --}}
            <div class="card mt-3 border-0 bg-light">
                <div class="card-body py-2">
                    <small class="text-muted">
                        <i class="icofont-info-square me-1"></i>
                        Besoin d'aide ? Contactez le service RH pour toute question sur les documents disponibles.
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugins-js')
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script>
@endpush

@push('js')
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialiser les tooltips Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialiser Select2
            AppModules.initSelect2(
                "#documentTypeSelect",
                'Sélectionnez un type de document', {
                    allowClear: true,
                    width: "100%"
                }
            );

            // Fonction pour gérer l'affichage selon le type
            function updateFormDisplay() {
                const selectedOption = $('#documentTypeSelect').find('option:selected');
                const documentType = selectedOption.data('type');
                const description = selectedOption.data('description');
                const label = selectedOption.text().trim();

                // Afficher/masquer l'info box
                if (selectedOption.val()) {
                    $('#documentTypeInfo').removeClass('d-none');
                    $('#typeInfoTitle').text(label);
                    $('#typeInfoDescription').text(description || 'Aucune description disponible.');

                    // Style différent pour les types exceptionnels
                    if (documentType === 'EXCEPTIONAL') {
                        $('#documentTypeInfo').addClass('exceptional');
                        $('#typeInfoIcon').removeClass('text-primary').addClass('text-warning');
                    } else {
                        $('#documentTypeInfo').removeClass('exceptional');
                        $('#typeInfoIcon').removeClass('text-warning').addClass('text-primary');
                    }
                } else {
                    $('#documentTypeInfo').addClass('d-none');
                }

                // Gérer l'affichage des champs de date
                if (documentType === 'EXCEPTIONAL') {
                    $('#dateRangeFields').hide();
                    $('#exceptionalMessage').removeClass('d-none');
                    $('#documentStartDate, #documentEndDate').val('');
                } else {
                    $('#dateRangeFields').show();
                    $('#exceptionalMessage').addClass('d-none');
                }
            }

            // Exécuter au chargement
            updateFormDisplay();

            // Gérer le changement de type
            $('#documentTypeSelect').on('change', function() {
                updateFormDisplay();
            });
        });
    </script>
@endpush
