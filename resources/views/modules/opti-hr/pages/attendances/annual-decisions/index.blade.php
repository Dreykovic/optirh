@extends('modules.opti-hr.pages.base')

@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/annual-decisions.css') }}">
@endpush

@section('admin-content')
    <div class="annual-decisions-container">
        <!-- Header Section -->
        <div class="decisions-header">
            <div class="decisions-title">
                <h1>Gestion des Decisions Annuelles</h1>
                <p>Decision courante et historique</p>
            </div>
            <div class="decisions-actions">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#decisionModal"
                    onclick="openDecisionModal()">
                    <i class="icofont-plus-circle me-1"></i>Nouvelle decision
                </button>
            </div>
        </div>

        <!-- Current Decision Section -->
        @if ($currentDecision)
            <div class="current-decision-section">
                <div class="current-decision-card" id="decision-card-{{ $currentDecision->id }}">
                    <div class="current-decision-header">
                        <div class="current-decision-badge">
                            <span class="badge">Decision Courante</span>
                            <span class="decision-number">N{{ $currentDecision->number }}/{{ $currentDecision->year }}</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" role="button"
                                        onclick="openDecisionModal({{ $currentDecision->id }})">
                                        <i class="icofont-edit me-2"></i>Modifier
                                    </a>
                                </li>
                                @if ($currentDecision->pdf)
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('decisions.downloadPdf', $currentDecision->id) }}">
                                            <i class="icofont-download me-2"></i>Telecharger PDF
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <button class="dropdown-item" onclick="window.print()">
                                        <i class="icofont-print me-2"></i>Imprimer
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="current-decision-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="current-decision-info flex-grow-1">
                                <div class="decision-info-item">
                                    <span class="decision-info-label">Numero</span>
                                    <span class="decision-info-value">{{ $currentDecision->number }}</span>
                                </div>
                                <div class="decision-info-item">
                                    <span class="decision-info-label">Annee</span>
                                    <span class="decision-info-value">{{ $currentDecision->year }}</span>
                                </div>
                                <div class="decision-info-item">
                                    <span class="decision-info-label">Reference</span>
                                    <span
                                        class="decision-info-value">{{ $currentDecision->reference ?: 'Non specifiee' }}</span>
                                </div>
                                <div class="decision-info-item">
                                    <span class="decision-info-label">Date</span>
                                    <span class="decision-info-value">@formatDateOnly($currentDecision->date)</span>
                                </div>
                                @if ($currentDecision->pdf)
                                    <div class="decision-info-item">
                                        <span class="decision-info-label">Document</span>
                                        <span class="decision-info-value">
                                            <a href="{{ route('decisions.downloadPdf', $currentDecision->id) }}"
                                                class="text-decoration-none">
                                                <i class="icofont-file-pdf text-danger me-1"></i>PDF disponible
                                            </a>
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="decision-stamp ms-4 d-none d-md-flex">
                                <div class="stamp-inner">
                                    <div class="stamp-number">{{ $currentDecision->number }}</div>
                                    <div class="stamp-year">{{ $currentDecision->year }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="current-decision-footer">
                        <div class="decision-validity">
                            <i class="icofont-check-circled me-2"></i>
                            <span>Decision active et en vigueur</span>
                        </div>
                        <div class="footer-actions">
                            @if ($currentDecision->pdf)
                                <a href="{{ route('decisions.downloadPdf', $currentDecision->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="icofont-download me-1"></i>Telecharger
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="current-decision-section">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="icofont-file-document"></i>
                    </div>
                    <h4>Aucune decision courante</h4>
                    <p>Vous n'avez pas encore defini de decision courante. Creez-en une ou definissez une decision existante
                        comme courante.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#decisionModal"
                        onclick="openDecisionModal()">
                        <i class="icofont-plus-circle me-1"></i>Creer une decision
                    </button>
                </div>
            </div>
        @endif

        <!-- History Table Section -->
        <div class="decisions-history-section">
            <div class="card history-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="icofont-history me-2"></i>Historique des decisions
                        </h5>
                        <span class="badge bg-secondary">{{ $decisions->total() }} decision(s)</span>
                    </div>
                </div>
                <div class="card-body">
                    @if ($decisions->count() > 0)
                        <div class="table-responsive">
                            <table id="decisionsTable" class="table decisions-table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Statut</th>
                                        <th>Numero</th>
                                        <th>Reference</th>
                                        <th>Annee</th>
                                        <th>Date</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($decisions as $decision)
                                        <tr id="decision-row-{{ $decision->id }}"
                                            class="parent {{ $decision->state === 'current' ? 'table-active' : '' }}">
                                            <td>
                                                @if ($decision->state === 'current')
                                                    <span class="badge bg-success">
                                                        <i class="icofont-star me-1"></i>Actif
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Archive</span>
                                                @endif
                                            </td>
                                            <td class="model-value">
                                                <strong>{{ $decision->number }}</strong>
                                            </td>
                                            <td>{{ $decision->reference ?: '-' }}</td>
                                            <td>{{ $decision->year }}</td>
                                            <td>@formatDateOnly($decision->date)</td>
                                            <td>
                                                @if ($decision->pdf)
                                                    <a href="{{ route('decisions.downloadPdf', $decision->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Telecharger PDF">
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
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" role="button"
                                                                onclick="openDecisionModal({{ $decision->id }})">
                                                                <i class="icofont-edit me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        @if ($decision->state !== 'current')
                                                            <li>
                                                                <div class="modelUpdateFormContainer dropdown-item set-current-btn"
                                                                    id="setDecisionToCurrentForm{{ $decision->id }}">
                                                                    <form
                                                                        data-model-update-url="{{ route('decisions.setCurrent', $decision->id) }}">
                                                                        <a role="button" class="modelUpdateBtn"
                                                                            alt="update decision status">
                                                                            <span class="normal-status">
                                                                                <i class="icofont-star me-2"></i>
                                                                                Definir comme courante
                                                                            </span>
                                                                            <span class="indicateur d-none">
                                                                                <span
                                                                                    class="spinner-grow spinner-grow-sm"
                                                                                    role="status"
                                                                                    aria-hidden="true"></span>
                                                                                Un instant...
                                                                            </span>
                                                                        </a>
                                                                    </form>
                                                                </div>
                                                            </li>
                                                        @endif
                                                        @if ($decision->pdf)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('decisions.downloadPdf', $decision->id) }}">
                                                                    <i class="icofont-download me-2"></i>Telecharger PDF
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if ($decision->state !== 'current')
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-danger modelDeleteBtn"
                                                                    data-model-action="delete"
                                                                    data-model-delete-url="{{ route('decisions.destroy', $decision->id) }}"
                                                                    data-model-parent-selector="tr.parent">
                                                                    <span class="normal-status">
                                                                        <i class="icofont-ui-delete"></i>
                                                                        Supprimer
                                                                    </span>
                                                                    <span class="indicateur d-none">
                                                                        <span class="spinner-grow spinner-grow-sm"
                                                                            role="status" aria-hidden="true"></span>
                                                                    </span>
                                                                </button>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($decisions->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $decisions->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="icofont-folder"></i>
                            </div>
                            <h4>Aucune decision</h4>
                            <p>Vous n'avez pas encore cree de decisions.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Single Dynamic Modal for Create/Edit -->
    <div class="modal fade decision-modal" id="decisionModal" tabindex="-1" aria-labelledby="decisionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="modelAddForm" data-model-add-url="{{ route('decisions.save') }}">
                    @csrf
                    <input type="hidden" name="decision_id" id="decisionId" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="decisionModalLabel">
                            <i class="icofont-plus-circle me-2" id="modalIcon"></i>
                            <span id="modalTitleText">Nouvelle decision</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="decisionNumber" class="form-label required">Numero de decision</label>
                            <input type="text" class="form-control" id="decisionNumber" name="number" required>
                        </div>

                        <div class="mb-3">
                            <label for="decisionYear" class="form-label required">Annee</label>
                            <input type="text" class="form-control" id="decisionYear" name="year"
                                value="{{ date('Y') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="decisionReference" class="form-label">Reference</label>
                            <input type="text" class="form-control" id="decisionReference" name="reference">
                        </div>

                        <div class="mb-3">
                            <label for="decisionDate" class="form-label required">Date</label>
                            <input type="date" class="form-control" id="decisionDate" name="date"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="decisionPdf" class="form-label">Document PDF</label>
                            <input type="file" class="form-control" id="decisionPdf" name="pdf" accept=".pdf">
                            <div class="form-text" id="pdfHelpText">
                                Formats acceptes: PDF (max. 10 Mo)
                            </div>
                        </div>

                        <input type="hidden" name="state" value="current">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" id="modelAddBtn" alt="Enregistrer Decision">
                            <span class="normal-status">
                                <i class="icofont-check me-1"></i>
                                <span id="submitBtnText">Enregistrer</span>
                            </span>
                            <span class="indicateur d-none">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Un instant...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Decisions Data for JavaScript -->
    <script type="application/json" id="decisionsData">
        @json($decisions->keyBy('id'))
    </script>
@endsection

@push('plugins-js')
    <script src="{{ asset('assets/bundles/dataTables.bundle.js') }}"></script>
@endpush

@push('js')
    <script src="{{ asset('app-js/attendances/annual-decisions/index.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
