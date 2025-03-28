@extends('modules.opti-hr.pages.base')

@section('admin-content')
    <style>
        /* Variables de couleurs ARCOP */
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
            --card-border-radius: 12px;
            --btn-border-radius: 6px;
            --transition: all 0.3s ease;
        }

        /* Modern UI for Annual Decision Page */
        .annual-decision-container {
            padding: 1.5rem;
            background-color: var(--arcop-bg-light);
        }

        /* Header Section */
        .decision-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, var(--arcop-green-light) 0%, transparent 100%);
            padding: 1.5rem;
            border-radius: var(--card-border-radius);
            border-left: 4px solid var(--arcop-green);
        }

        .decision-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--arcop-green-dark);
        }

        .decision-title p {
            margin-bottom: 0;
            color: var(--arcop-text-light);
        }

        .decision-actions .btn-primary {
            background: var(--arcop-green);
            border: none;
            border-radius: var(--btn-border-radius);
            box-shadow: 0 4px 6px rgba(55, 160, 69, 0.2);
            transition: var(--transition);
        }

        .decision-actions .btn-primary:hover {
            background-color: var(--arcop-green-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(55, 160, 69, 0.3);
        }

        .decision-actions .btn-outline-primary {
            color: var(--arcop-green);
            border-color: var(--arcop-green);
            border-radius: var(--btn-border-radius);
            transition: var(--transition);
        }

        .decision-actions .btn-outline-primary:hover {
            background-color: var(--arcop-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(55, 160, 69, 0.2);
        }

        /* Decision Card */
        .decision-content {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .decision-card {
            background-color: #fff;
            border-radius: var(--card-border-radius);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 800px;
            overflow: hidden;
            transition: var(--transition);
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
            background-color: var(--arcop-green-light);
        }

        .decision-card-badge span {
            background: var(--arcop-green);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-options {
            background: none;
            border: none;
            color: var(--arcop-green-dark);
            font-size: 1.25rem;
            padding: 0.25rem 0.5rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .btn-options:hover {
            color: var(--arcop-green);
        }

        .decision-card-body {
            padding: 2rem;
        }

        /* Decision Paper */
        .decision-paper {
            background-color: white;
            border: 1px solid var(--arcop-border-light);
            border-radius: 8px;
            padding: 2rem;
            position: relative;
        }

        .decision-paper-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .company-logo {
            margin-bottom: 1rem;
            color: var(--arcop-green);
            position: relative;
        }

        .company-logo::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            background-color: var(--arcop-green-light);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.3;
        }

        .decision-paper-title h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 2px;
            color: var(--arcop-text-dark);
            background: linear-gradient(to right, var(--arcop-green) 0%, var(--arcop-green-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .decision-ref {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--arcop-border-light);
        }

        .ref-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--arcop-green);
        }

        .ref-code {
            font-size: 1.1rem;
            color: var(--arcop-text-light);
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
            padding: 0.8rem;
            border-radius: 8px;
            background-color: var(--arcop-bg-light);
            border-left: 3px solid var(--arcop-green-light);
            transition: var(--transition);
        }

        .meta-item:hover {
            transform: translateX(5px);
            border-left-color: var(--arcop-green);
        }

        .meta-label {
            font-size: 0.85rem;
            color: var(--arcop-text-light);
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--arcop-text-dark);
        }

        /* Decision Stamp */
        .decision-stamp {
            width: 150px;
            height: 150px;
            border: 3px solid var(--arcop-green);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            background: linear-gradient(135deg, rgba(55, 160, 69, 0.05) 0%, rgba(55, 160, 69, 0.1) 100%);
        }

        .decision-stamp::before {
            content: '';
            position: absolute;
            top: -10px;
            right: -10px;
            bottom: -10px;
            left: -10px;
            border: 1px dashed var(--arcop-green);
            border-radius: 50%;
            opacity: 0.5;
        }

        .decision-stamp::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            width: 120%;
            height: 120%;
            border: 1px solid var(--arcop-yellow);
            border-radius: 50%;
            opacity: 0.3;
            animation: rotateStamp 30s linear infinite;
        }

        .stamp-inner {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .stamp-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--arcop-green);
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stamp-date {
            font-size: 0.9rem;
            color: var(--arcop-text-dark);
            font-weight: 600;
        }

        /* PDF Section */
        .decision-pdf {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            border: 1px dashed var(--arcop-border-light);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 2rem;
        }

        .pdf-info {
            display: flex;
            align-items: center;
        }

        .pdf-info i {
            color: var(--arcop-red) !important;
        }

        .decision-card-footer {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--arcop-bg-light);
        }

        .decision-validity {
            display: flex;
            align-items: center;
            color: var(--arcop-text-dark);
            font-size: 0.9rem;
        }

        .decision-validity i {
            color: var(--arcop-green) !important;
        }

        .footer-actions {
            display: flex;
            gap: 0.5rem;
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
            border-radius: var(--card-border-radius);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .empty-illustration {
            font-size: 5rem;
            color: var(--arcop-green-light);
            margin-bottom: 1.5rem;
            position: relative;
        }

        .empty-illustration::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            background-color: var(--arcop-green-light);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.3;
        }

        .empty-decision h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--arcop-text-dark);
        }

        .empty-decision p {
            color: var(--arcop-text-light);
            margin-bottom: 1.5rem;
            max-width: 400px;
        }

        /* Dropdown Menu */
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

        .dropdown-item.text-danger {
            color: var(--arcop-red) !important;
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(231, 49, 55, 0.05);
        }

        /* Badge */
        .badge.bg-success {
            background-color: var(--arcop-green) !important;
            padding: 0.5em 0.8em;
            border-radius: 30px;
        }

        /* Animations */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(55, 160, 69, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(55, 160, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(55, 160, 69, 0);
            }
        }

        @keyframes rotateStamp {
            from {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .decision-stamp {
            animation: pulse 2s infinite;
        }

        /* Decorative elements */
        .decision-paper::before {
            content: '';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, transparent 0%, var(--arcop-yellow-light) 100%);
            opacity: 0.5;
        }

        .decision-paper::after {
            content: '';
            position: absolute;
            bottom: 10px;
            left: 10px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--arcop-green-light) 0%, transparent 100%);
            opacity: 0.5;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .decision-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .decision-actions {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .decision-actions .btn {
                width: 100%;
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

            .decision-paper::before,
            .decision-paper::after {
                display: none;
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
                <p>Gestion des décisions courantes de l'entreprise</p>
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
                                        <i class="icofont-file-pdf fs-4 me-2"></i>
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
                            <i class="icofont-check-circled me-2"></i>
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
