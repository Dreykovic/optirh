@extends('modules.opti-hr.pages.base')

@section('admin-content')
    <style>
        /* Decision List Styles */
        .decisions-list-container {
            padding: 1.5rem;
        }

        .decisions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .decisions-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #333;
        }

        .decisions-title p {
            margin-bottom: 0;
        }

        .search-box {
            position: relative;
            width: 250px;
        }

        .search-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #858796;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: #e3e6f0;
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #5a5c69;
        }

        .empty-state p {
            color: #858796;
            margin-bottom: 1rem;
        }

        /* Animations and hover effects */
        .table-hover tbody tr:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.05);
            z-index: 1;
            position: relative;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #3e60cc 100%);
            border: none;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(78, 115, 223, 0.25);
        }

        /* Table styles */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table td,
        .table th {
            border-bottom: 1px solid #e3e6f0;
            padding: 0.75rem 1rem;
        }

        .table thead th {
            background-color: #f8f9fc;
            border-bottom: 2px solid #e3e6f0;
            color: #5a5c69;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }

        .table-active {
            background-color: rgba(78, 115, 223, 0.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .decisions-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .decisions-actions {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 0.5rem;
            }

            .decisions-actions .btn {
                width: 100%;
            }

            .search-box {
                width: 100%;
                margin-top: 1rem;
            }
        }
    </style>
    <div class="decisions-list-container">
        <!-- Header Section -->
        <div class="decisions-header">
            <div class="decisions-title">
                <h1>Historique des Décisions</h1>
                <p class="text-muted">Liste de toutes les décisions annuelles</p>
            </div>
            <div class="decisions-actions">
                <a href="{{ route('decisions.show') }}" class="btn btn-outline-primary me-2">
                    <i class="icofont-eye me-1"></i>Décision courante
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDecisionModal">
                    <i class="icofont-plus-circle me-1"></i>Nouvelle décision
                </button>
            </div>
        </div>

        <!-- Decisions List -->
        <div class="decisions-content">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Toutes les décisions</h5>
                        <div class="search-box">
                            <input type="text" id="searchDecisions" class="form-control" placeholder="Rechercher...">
                            <i class="icofont-search-1 search-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($decisions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Statut</th>
                                        <th>Numéro</th>
                                        <th>Référence</th>
                                        <th>Année</th>
                                        <th>Date</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($decisions as $decision)
                                        <tr id="decision-row-{{ $decision->id }}"
                                            class="{{ $decision->state === 'current' ? 'table-active' : '' }}">
                                            <td>
                                                @if ($decision->state === 'current')
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-secondary">Archivé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $decision->number }}</strong>
                                            </td>
                                            <td>{{ $decision->reference ?: '-' }}</td>
                                            <td>{{ $decision->year }}</td>
                                            <td>@formatDateOnly($decision->date)</td>
                                            <td>
                                                @if ($decision->pdf)
                                                    <a href="{{ route('decisions.downloadPdf', $decision->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="icofont-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('decisions.detail', $decision->id) }}">
                                                                <i class="icofont-eye-alt me-2"></i>Détails
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('decisions.edit', $decision->id) }}">
                                                                <i class="icofont-edit me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        @if ($decision->state !== 'current')
                                                            <li>
                                                                <button class="dropdown-item"
                                                                    onclick="setCurrentDecision({{ $decision->id }})">
                                                                    <i class="icofont-star me-2"></i>Définir comme courante
                                                                </button>
                                                            </li>
                                                        @endif
                                                        @if ($decision->pdf)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('decisions.downloadPdf', $decision->id) }}">
                                                                    <i class="icofont-download me-2"></i>Télécharger PDF
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item text-danger"
                                                                onclick="confirmDeleteDecision({{ $decision->id }})">
                                                                <i class="icofont-trash me-2"></i>Supprimer
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="icofont-folder"></i>
                            </div>
                            <h4>Aucune décision</h4>
                            <p>Vous n'avez pas encore créé de décisions.</p>
                            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal"
                                data-bs-target="#addDecisionModal">
                                <i class="icofont-plus-circle me-1"></i>Créer une décision
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Decision Modal -->
    <div class="modal fade" id="addDecisionModal" tabindex="-1" aria-labelledby="addDecisionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="decisionForm" action="{{ route('decisions.save') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDecisionModalLabel">Créer une nouvelle décision</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="number" class="form-label">Numéro de décision*</label>
                            <input type="text" class="form-control" id="number" name="number" required>
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label">Année*</label>
                            <input type="text" class="form-control" id="year" name="year"
                                value="{{ date('Y') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="reference" class="form-label">Référence</label>
                            <input type="text" class="form-control" id="reference" name="reference">
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date*</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="pdf" class="form-label">Document PDF</label>
                            <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="setAsCurrent" name="state"
                                    value="current" checked>
                                <label class="form-check-label" for="setAsCurrent">
                                    Définir comme décision courante
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette décision ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        // Handle AJAX form submission
        $(document).ready(function() {
            // Search functionality
            $("#searchDecisions").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Form submission
            $('#decisionForm').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var formData = new FormData(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.ok) {
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Succès!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(function() {
                                // Reload the page to show the updated decision
                                window.location.reload();
                            });
                        } else {
                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';

                        // Construct error message
                        if (errors) {
                            $.each(errors, function(key, value) {
                                errorMessage += value + '<br>';
                            });
                        } else {
                            errorMessage =
                                'Une erreur s\'est produite lors de l\'enregistrement de la décision.';
                        }

                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur de validation',
                            html: errorMessage
                        });
                    }
                });
            });
        });

        // Set as current decision
        function setCurrentDecision(id) {
            $.ajax({
                url: "{{ route('decisions.setCurrent', '') }}/" + id,
                type: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(function() {
                            // Reload the page to show the updated status
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur!',
                        text: 'Une erreur s\'est produite lors de la mise à jour de la décision courante.'
                    });
                }
            });
        }

        // Delete confirmation
        function confirmDeleteDecision(id) {
            $('#confirmDeleteModal').modal('show');

            $('#confirmDeleteBtn').off('click').on('click', function() {
                $.ajax({
                    url: "{{ route('decisions.delete', '') }}/" + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.ok) {
                            $('#confirmDeleteModal').modal('hide');

                            // Remove the row from the table
                            $('#decision-row-' + id).fadeOut(300, function() {
                                $(this).remove();

                                // Check if there are no more rows and show empty state
                                if ($('table tbody tr').length === 0) {
                                    location.reload();
                                }
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'Succès!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            $('#confirmDeleteModal').modal('hide');

                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        $('#confirmDeleteModal').modal('hide');

                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur!',
                            text: 'Une erreur s\'est produite lors de la suppression de la décision.'
                        });
                    }
                });
            });
        }
    </script>
@endpush

@push('css')
@endpush
