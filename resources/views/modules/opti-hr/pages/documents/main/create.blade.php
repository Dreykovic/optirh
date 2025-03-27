@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link href={{ asset('assets/plugins/select2/css/select2.min.css') }} rel="stylesheet">
@endsection

@section('admin-content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <h3 class="mb-0"><i class="fa fa-file-text me-2"></i>Soumettre Une Demande De Document</h3>
                </div>
                <div class="deadline-form">
                    <form id="modelAddForm" data-model-add-url="{{ route('documents.save') }}">
                        @csrf
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label required fw-bold" for="documentTypeSelect">Type de document</label>
                                <select class="form-select form-select-lg" id="documentTypeSelect" name="document_type">
                                    <option value="" selected disabled>Veuillez sélectionner un type de document
                                    </option>
                                    @foreach ($documentTypes as $documentType)
                                        <option value="{{ $documentType->id }}" data-type="{{ $documentType->type }}">
                                            {{ $documentType->label }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted" id="documentTypeDescription"></div>
                            </div>

                            <div class="row g-3 mb-3" id="dateRangeFields">
                                <div class="col-sm-6">
                                    <label for="documentStartDate" class="form-label fw-bold">Date de début</label>
                                    <input type="date" class="form-control" id="documentStartDate" name="start_date">
                                </div>
                                <div class="col-sm-6">
                                    <label for="documentEndDate" class="form-label fw-bold">Date de fin</label>
                                    <input type="date" class="form-control" id="documentEndDate" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-end gap-2 py-3">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fa fa-times me-1"></i>Annuler
                            </button>
                            <button type="submit" class="btn btn-primary" id="modelAddBtn">
                                <span class="normal-status">
                                    <i class="fa fa-paper-plane me-1"></i>Soumettre
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
@endsection

@push('plugins-js')
    <script src={{ asset('assets/plugins/select2/js/select2.min.js') }}></script>
@endpush

@push('js')
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script>
        $(document).ready(function() {


            AppModules.initSelect2(
                "#documentTypeSelect",
                'Sélectionnez un type de document', {
                    allowClear: true,
                    width: "100%"
                }
            );

            // Vérifier l'état initial au chargement de la page
            function checkExceptionalType() {
                const selectedOption = $('#documentTypeSelect').find('option:selected');
                const documentType = selectedOption.data('type');

                // Vérifier si c'est un type exceptionnel
                if (documentType === 'EXCEPTIONAL') {
                    $('#dateRangeFields').hide();
                    // Réinitialiser les valeurs des dates
                    $('#documentStartDate, #documentEndDate').val('');
                } else {
                    $('#dateRangeFields').show();
                }
            }

            // Exécuter la vérification au chargement
            checkExceptionalType();

            // Gérer le changement de type de document
            $('#documentTypeSelect').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const documentType = selectedOption.data('type');
                const documentTypeId = selectedOption.val();

                // Afficher la description si disponible
                const description = getDocumentTypeDescription(documentTypeId);
                if (description) {
                    $('#documentTypeDescription').text(description).show();
                } else {
                    $('#documentTypeDescription').hide();
                }

                // Vérifier si c'est un type exceptionnel
                checkExceptionalType();
            });

            // Fonction pour récupérer la description du type de document (à implémenter côté serveur)
            function getDocumentTypeDescription(documentTypeId) {
                // Cette fonction devrait idéalement faire une requête AJAX pour récupérer la description
                // Pour l'instant, nous utilisons une approche simplifiée
                const descriptions = {};
                @foreach ($documentTypes as $documentType)
                    descriptions[{{ $documentType->id }}] = "{{ $documentType->description }}";
                @endforeach

                return descriptions[documentTypeId] || '';
            }

        });
    </script>
@endpush
