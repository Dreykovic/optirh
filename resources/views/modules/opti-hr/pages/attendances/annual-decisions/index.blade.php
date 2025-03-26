@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/responsive.dataTables.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}>
@endsection
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
