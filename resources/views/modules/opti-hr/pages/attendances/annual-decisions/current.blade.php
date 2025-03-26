@extends('modules.opti-hr.pages.base')

@section('admin-content')
    <style>
        /* Modern UI for Annual Decision Page */
        .annual-decision-container {
            padding: 1.5rem;
        }

        /* Header Section */
        .decision-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .decision-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #333;
        }

        .decision-title p {
            margin-bottom: 0;
        }

        .decision-actions .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #3e60cc 100%);
            border: none;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.2);
            transition: all 0.3s ease;
        }

        .decision-actions .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(78, 115, 223, 0.25);
        }

        /* Decision Card */
        .decision-content {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .decision-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 800px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .decision-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
        }

        .decision-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .decision-card-badge span {
            background: linear-gradient(135deg, #36b9cc 0%, #1e95a7 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-options {
            background: none;
            border: none;
            color: #6e707e;
            font-size: 1.25rem;
            padding: 0.25rem 0.5rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .btn-options:hover {
            color: #4e73df;
        }

        .decision-card-body {
            padding: 2rem;
        }

        /* Decision Paper */
        .decision-paper {
            background-color: #f8f9fc;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 2rem;
            position: relative;
        }

        .decision-paper-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .company-logo {
            margin-bottom: 1rem;
            color: #4e73df;
        }

        .decision-paper-title h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 2px;
            color: #333;
        }

        .decision-ref {
            margin-top: 1rem;
        }

        .ref-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #4e73df;
        }

        .ref-code {
            font-size: 1.1rem;
            color: #5a5c69;
            font-weight: 600;
        }

        .decision-paper-content {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .decision-meta {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 0.85rem;
            color: #858796;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }

        /* Decision Stamp */
        .decision-stamp {
            width: 150px;
            height: 150px;
            border: 3px solid #4e73df;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .decision-stamp::before {
            content: '';
            position: absolute;
            top: -10px;
            right: -10px;
            bottom: -10px;
            left: -10px;
            border: 1px dashed #4e73df;
            border-radius: 50%;
            opacity: 0.5;
        }

        .stamp-inner {
            text-align: center;
        }

        .stamp-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4e73df;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stamp-date {
            font-size: 0.9rem;
            color: #5a5c69;
            font-weight: 600;
        }

        /* PDF Section */
        .decision-pdf {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            border: 1px dashed #e3e6f0;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 2rem;
        }

        .pdf-info {
            display: flex;
            align-items: center;
        }

        .decision-card-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .decision-validity {
            display: flex;
            align-items: center;
            color: #5a5c69;
            font-size: 0.9rem;
        }

        .footer-actions {
            display: flex;
        }

        /* Empty State */
        .empty-decision {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 4rem 2rem;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .empty-illustration {
            font-size: 5rem;
            color: #e3e6f0;
            margin-bottom: 1.5rem;
        }

        .empty-decision h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #5a5c69;
        }

        .empty-decision p {
            color: #858796;
            margin-bottom: 1.5rem;
            max-width: 400px;
        }

        /* Animation for stamp */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(78, 115, 223, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(78, 115, 223, 0);
            }
        }

        .decision-stamp {
            animation: pulse 2s infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .decision-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .decision-paper-content {
                flex-direction: column;
                gap: 2rem;
                align-items: center;
            }

            .decision-meta {
                width: 100%;
            }

            .decision-card-footer {
                flex-direction: column;
                gap: 1rem;
            }

            .footer-actions {
                width: 100%;
                justify-content: center;
                gap: 0.5rem;
            }
        }

        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .decision-paper,
            .decision-paper * {
                visibility: visible;
            }

            .decision-paper {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                padding: 2cm;
                box-shadow: none;
                border: none;
            }

            .decision-stamp {
                animation: none;
            }

            .decision-pdf {
                display: none;
            }
        }
    </style>
    <div class="annual-decision-container">
        <!-- Header Section -->
        <div class="decision-header">
            <div class="decision-title">
                <h1>Décision Annuelle</h1>
                <p class="text-muted">Gestion des décisions courantes de l'entreprise</p>
            </div>
            <div class="decision-actions">
                @if ($decision)
                    <a href="{{ route('decisions.index') }}" class="btn btn-outline-primary me-2">
                        <i class="icofont-history me-1"></i>Historique
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addOrEditDecisionModal{{ $decision->id }}">
                        <i class="icofont-refresh me-1"></i>Modifier la décision
                    </button>
                @else
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addDecisionModal">
                        <i class="icofont-plus-circle me-1"></i>Créer une décision
                    </button>
                @endif
            </div>
        </div>

        <!-- Decision Content -->
        @if ($decision)
            <div class="decision-content">
                <div class="decision-card" id="decision-card-{{ $decision->id }}">
                    <div class="decision-card-header">
                        <div class="decision-card-badge">
                            <span>Décision Courante</span>
                        </div>
                        <div class="decision-options">
                            <div class="dropdown">
                                <button class="btn-options" type="button" id="decisionOptions" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="icofont-options"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="decisionOptions">
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#addDecisionModal">
                                            <i class="icofont-edit me-2"></i>Modifier
                                        </button>
                                    </li>
                                    @if ($decision->pdf)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('decisions.downloadPdf', $decision->id) }}">
                                                <i class="icofont-download me-2"></i>Télécharger le PDF
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <button type="button" class="dropdown-item text-danger"
                                            onclick="confirmDeleteDecision({{ $decision->id }})">
                                            <i class="icofont-trash me-2"></i>Supprimer
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="decision-card-body">
                        <div class="decision-paper">
                            <div class="decision-paper-header">
                                <div class="company-logo">
                                    <!-- Company Logo Here -->
                                    <i class="icofont-building-alt fs-1"></i>
                                </div>

                                <div class="decision-paper-title">
                                    <h2>DÉCISION</h2>
                                </div>

                                <div class="decision-ref">
                                    <div class="ref-number">N° {{ $decision->number }}</div>
                                    <div class="ref-code">
                                        {{ "{$decision->number}/{$decision->year}/{$decision->reference}" }}</div>
                                </div>
                            </div>

                            <div class="decision-paper-content">
                                <div class="decision-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Date d'émission</span>
                                        <span class="meta-value">@formatDateOnly($decision->date)</span>
                                    </div>

                                    <div class="meta-item">
                                        <span class="meta-label">Année</span>
                                        <span class="meta-value">{{ $decision->year }}</span>
                                    </div>

                                    <div class="meta-item">
                                        <span class="meta-label">Référence</span>
                                        <span class="meta-value">{{ $decision->reference ?: 'Non spécifiée' }}</span>
                                    </div>

                                    <div class="meta-item">
                                        <span class="meta-label">Status</span>
                                        <span class="meta-value">
                                            <span class="badge bg-success">Actif</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="decision-stamp">
                                    <div class="stamp-inner">
                                        <div class="stamp-number">{{ $decision->number }}</div>
                                        <div class="stamp-date">@formatDateOnly($decision->date)</div>
                                    </div>
                                </div>
                            </div>

                            @if ($decision->pdf)
                                <div class="decision-pdf mt-4">
                                    <div class="pdf-info">
                                        <i class="icofont-file-pdf fs-4 text-danger me-2"></i>
                                        <span>Document PDF attaché</span>
                                    </div>
                                    <a href="{{ route('decisions.downloadPdf', $decision->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="icofont-download me-1"></i>Télécharger
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="decision-card-footer">
                        <div class="decision-validity">
                            <i class="icofont-check-circled text-success me-2"></i>
                            <span>Décision active et en vigueur</span>
                        </div>

                        <div class="footer-actions">
                            <button class="btn btn-outline-primary" onclick="window.print()">
                                <i class="icofont-print me-1"></i>
                                Imprimer
                            </button>

                            @if ($decision->pdf)
                                <a href="{{ route('decisions.downloadPdf', $decision->id) }}" class="btn btn-primary ms-2">
                                    <i class="icofont-download me-1"></i>
                                    Télécharger
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-decision">
                <div class="empty-illustration">
                    <i class="icofont-file-document"></i>
                </div>
                <h3>Aucune décision enregistrée</h3>
                <p>Vous n'avez pas encore défini de décision courante pour cette période.</p>
                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal"
                    data-bs-target="#addDecisionModal">
                    <i class="icofont-plus-circle me-2"></i>Créer une décision
                </button>
            </div>
        @endif
    </div>
    @if ($decision)
        <!-- Edit Decision Modal -->
        @include('modules.opti-hr.pages.attendances.annual-decisions.edit')
    @else
        <!-- Edit Decision Modal -->
        @include('modules.opti-hr.pages.attendances.annual-decisions.create')
    @endif

@endsection

@push('js')
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
@endpush
