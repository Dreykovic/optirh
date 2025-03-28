@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
@section('admin-content')
    <style>
        :root {
            --arcop-green: #37a045;
            --arcop-green-light: #b5d8b7;
            --arcop-green-dark: #2a7b33;
            --arcop-red: #e73137;
            --arcop-yellow: #ffd700;
            --arcop-yellow-light: #fff5b1;
            --arcop-text-dark: #333333;
            --arcop-text-light: #6c757d;
            --arcop-bg-light: #f8f9fa;
            --arcop-border-light: #dee2e6;
            --transition: all 0.25s ease;
            --card-border-radius: 12px;
            --btn-border-radius: 6px;
        }

        /* Conteneur principal */
        .decisions-list-container {
            padding: 1.5rem;
            background-color: var(--arcop-bg-light);
        }

        /* En-tête */
        .decisions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, var(--arcop-green-light) 0%, transparent 100%);
            padding: 1.5rem;
            border-radius: var(--card-border-radius);
            border-left: 4px solid var(--arcop-green);
        }

        .decisions-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--arcop-green-dark);
        }

        .decisions-title p {
            margin-bottom: 0;
            color: var(--arcop-text-light);
        }

        /* Boutons d'action */
        .btn-outline-primary {
            color: var(--arcop-green);
            border-color: var(--arcop-green);
            border-radius: var(--btn-border-radius);
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background-color: var(--arcop-green);
            border-color: var(--arcop-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(55, 160, 69, 0.2);
        }

        .btn-primary {
            background: var(--arcop-green);
            border: none;
            border-radius: var(--btn-border-radius);
            box-shadow: 0 4px 6px rgba(55, 160, 69, 0.2);
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--arcop-green-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(55, 160, 69, 0.25);
        }

        /* Carte */
        .card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--arcop-border-light);
            padding: 1rem 1.5rem;
        }

        .card-header h5 {
            color: var(--arcop-text-dark);
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Table */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 0;
        }

        .table td,
        .table th {
            border-bottom: 1px solid var(--arcop-border-light);
            padding: 1rem 1.25rem;
            vertical-align: middle;
        }

        .table thead th {
            background-color: var(--arcop-bg-light);
            border-bottom: 2px solid var(--arcop-border-light);
            color: var(--arcop-text-dark);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.03em;
        }

        .table-hover tbody tr {
            transition: var(--transition);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(55, 160, 69, 0.03);
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            z-index: 1;
            position: relative;
        }

        .table-active {
            background-color: rgba(55, 160, 69, 0.06) !important;
        }

        /* Badges */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 30px;
        }

        .bg-success {
            background-color: var(--arcop-green) !important;
        }

        .bg-secondary {
            background-color: var(--arcop-text-light) !important;
        }

        /* Boutons dans le tableau */
        .btn-sm {
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
            border-radius: var(--btn-border-radius);
        }

        .btn-outline-secondary {
            transition: var(--transition);
        }

        .btn-outline-secondary:hover {
            background-color: var(--arcop-bg-light);
        }

        /* Menu déroulant */
        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
        }

        .dropdown-item {
            padding: 0.6rem 1rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(55, 160, 69, 0.05);
        }

        .dropdown-item i {
            width: 18px;
            text-align: center;
            margin-right: 8px;
        }

        .dropdown-divider {
            margin: 0.3rem 0;
        }

        /* État vide */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--arcop-green-light);
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--arcop-text-dark);
        }

        .empty-state p {
            color: var(--arcop-text-light);
            margin-bottom: 1.5rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Icônes personnalisées */
        .icofont-star {
            color: var(--arcop-yellow);
        }

        .icofont-download {
            color: var(--arcop-green);
        }

        .icofont-eye {
            color: var(--arcop-green);
        }

        .icofont-plus-circle {
            color: white;
        }

        .icofont-ui-delete {
            color: var(--arcop-red) !important;
        }

        .text-danger {
            color: var(--arcop-red) !important;
        }

        /* Définir comme courante - hover */
        #setDecisionToCurrentForm a:hover {
            background-color: var(--arcop-yellow-light) !important;
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
        }
    </style>
    <div class="decisions-list-container">
        <!-- Header Section -->
        <div class="decisions-header">
            <div class="decisions-title">
                <h1>Historique des Décisions</h1>
                <p>Liste de toutes les décisions annuelles</p>
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
                    </div>
                </div>
                <div class="card-body">
                    @if ($decisions->count() > 0)
                        <div class="table-responsive">
                            <table id="decisionsTable" class="table table-hover align-middle">
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
                                            class="parent {{ $decision->state === 'current' ? 'table-active' : '' }} ">
                                            <td>
                                                @if ($decision->state === 'current')
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-secondary">Archivé</span>
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
                                                            <a class="dropdown-item" role="button" data-bs-toggle="modal"
                                                                data-bs-target="#addOrEditDecisionModal{{ $decision->id }}">
                                                                <i class="icofont-edit me-2"></i>Modifier
                                                            </a>
                                                        </li>
                                                        @if ($decision->state !== 'current')
                                                            <li>
                                                                <div class="modelUpdateFormContainer dropdown-item"
                                                                    id="setDecisionToCurrentForm{{ $decision->id }}">
                                                                    <form
                                                                        data-model-update-url="{{ route('decisions.setCurrent', $decision->id) }}">
                                                                        <a role="button" class="modelUpdateBtn"
                                                                            atl="update decision status">
                                                                            <span class="normal-status">
                                                                                <i class="icofont-star me-2"></i>
                                                                                <span class="d-none d-sm-none d-md-inline">
                                                                                    Définir comme courante
                                                                                </span>
                                                                            </span>
                                                                            <span class="indicateur d-none">
                                                                                <span class="spinner-grow spinner-grow-sm"
                                                                                    role="status"
                                                                                    aria-hidden="true"></span>
                                                                                Un Instant...
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
                                                                    <i class="icofont-download me-2"></i>Télécharger PDF
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item text-danger modelDeleteBtn"
                                                                data-model-action="delete"
                                                                data-model-delete-url={{ route('decisions.destroy', $decision->id) }}
                                                                data-model-parent-selector="tr.parent">
                                                                <span class="normal-status">
                                                                    <i class="icofont-ui-delete text-danger"></i>
                                                                    Supprimer
                                                                </span>
                                                                <span class="indicateur d-none">
                                                                    <span class="spinner-grow spinner-grow-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                            @include('modules.opti-hr.pages.attendances.annual-decisions.edit')
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
    @include('modules.opti-hr.pages.attendances.annual-decisions.create')
@endsection
@push('plugins-js')
    <script src={{ asset('assets/bundles/dataTables.bundle.js') }}></script>
@endpush
@push('js')
    <script src="{{ asset('app-js/attendances/annual-decisions/table.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>
@endpush
