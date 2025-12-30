<ul class="menu-list">

    {{-- Section: Principal --}}
    <li class="menu-section">
        <span class="section-title">Principal</span>
    </li>

    {{-- Dashboard --}}
    <li class="menu-item">
        <a class="m-link {{ Request::is('opti-hr') || Request::is('opti-hr/dashboard') ? 'active' : '' }}"
           href="{{ route('opti-hr.home') }}">
            <i class="icofont-ui-home"></i>
            <span>Tableau de bord</span>
        </a>
    </li>

    {{-- Espace Collaboratif --}}
    <li class="menu-item">
        <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/publications') ? 'active' : '' }}"
           href="{{ route('publications.config.index') }}">
            <i class="icofont-newspaper"></i>
            <span>Espace Collaboratif</span>
        </a>
    </li>

    {{-- Section: Gestion --}}
    <li class="menu-section">
        <span class="section-title">Gestion</span>
    </li>

    {{-- Absences --}}
    <li class="menu-item has-submenu {{ Str::startsWith(request()->path(), 'opti-hr/attendances') ? 'expanded' : '' }}">
        <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/attendances') ? 'active' : '' }}"
           data-bs-toggle="collapse"
           data-bs-target="#menu-absences"
           href="#"
           aria-expanded="{{ Str::startsWith(request()->path(), 'opti-hr/attendances') ? 'true' : 'false' }}">
            <i class="icofont-calendar"></i>
            <span>Absences</span>
            <i class="arrow icofont-rounded-down"></i>
        </a>
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/attendances') ? 'show' : '' }}"
            id="menu-absences">
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/attendances/absences/request/create') ? 'active' : '' }}"
                   href="{{ route('absences.create') }}">
                    <span>Soumettre une demande</span>
                </a>
            </li>
            @if (auth()->user()->hasEmployee() && auth()->user()->getCurrentDuty())
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/attendances/absences/requests') ? 'active' : '' }}"
                       href="{{ route('absences.requests') }}">
                        <span>Liste des demandes</span>
                    </a>
                </li>
            @endif
            @can('configurer-une-absence')
                <li>
                    <a class="ms-link {{ Request::is('opti-hr/attendances/absence-types/list') ? 'active' : '' }}"
                       href="{{ route('absenceTypes.index') }}">
                        <span>Types d'absences</span>
                    </a>
                </li>
            @endcan
            @can('voir-un-all')
                <li>
                    <a class="ms-link {{ Request::is('opti-hr/attendances/holidays/list*') ? 'active' : '' }}"
                       href="{{ route('holidays.index') }}">
                        <span>Jours fériés</span>
                    </a>
                </li>
            @endcan
            @can('configurer-une-absence')
                <li>
                    <a class="ms-link {{ Request::is('opti-hr/publications/annual-decisions*') ? 'active' : '' }}"
                       href="{{ route('decisions.index') }}">
                        <span>Décisions annuelles</span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>

    {{-- Documents --}}
    <li class="menu-item has-submenu {{ Str::startsWith(request()->path(), 'opti-hr/documents') ? 'expanded' : '' }}">
        <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/documents') ? 'active' : '' }}"
           data-bs-toggle="collapse"
           data-bs-target="#menu-documents"
           href="#"
           aria-expanded="{{ Str::startsWith(request()->path(), 'opti-hr/documents') ? 'true' : 'false' }}">
            <i class="icofont-file-document"></i>
            <span>Documents</span>
            <i class="arrow icofont-rounded-down"></i>
        </a>
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/documents') ? 'show' : '' }}"
            id="menu-documents">
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/documents/requests/create') ? 'active' : '' }}"
                   href="{{ route('documents.create') }}">
                    <span>Nouvelle demande</span>
                </a>
            </li>
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/documents/requests/index') ? 'active' : '' }}"
                   href="{{ route('documents.requests') }}">
                    <span>Mes documents</span>
                </a>
            </li>
            @can('configurer-un-document')
                <li>
                    <a class="ms-link {{ Request::is('opti-hr/documents/document-types/list') ? 'active' : '' }}"
                       href="{{ route('documentTypes.index') }}">
                        <span>Types de documents</span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>

    {{-- Section: Administration --}}
    @if(auth()->user()->hasRole('ADMIN') || auth()->user()->can('voir-un-credentials') || auth()->user()->can('voir-un-employee'))
        <li class="menu-section">
            <span class="section-title">Administration</span>
        </li>
    @endif

    {{-- Identifiants - Accessible aux GRH --}}
    @can('voir-un-credentials')
        <li class="menu-item">
            <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/credentials') ? 'active' : '' }}"
               href="{{ route('credentials.index') }}">
                <i class="icofont-users-alt-5"></i>
                <span>Identifiants</span>
            </a>
        </li>
    @endcan

    {{-- Système - ADMIN uniquement --}}
    @role('ADMIN')
        <li class="menu-item has-submenu {{ Str::startsWith(request()->path(), 'opti-hr/users-management/roles') || Str::startsWith(request()->path(), 'opti-hr/users-management/permissions') || Str::startsWith(request()->path(), 'opti-hr/users-management/activity-logs') ? 'expanded' : '' }}">
            <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/roles') || Str::startsWith(request()->path(), 'opti-hr/users-management/permissions') || Str::startsWith(request()->path(), 'opti-hr/users-management/activity-logs') ? 'active' : '' }}"
               data-bs-toggle="collapse"
               data-bs-target="#menu-system"
               href="#"
               aria-expanded="{{ Str::startsWith(request()->path(), 'opti-hr/users-management/roles') || Str::startsWith(request()->path(), 'opti-hr/users-management/permissions') || Str::startsWith(request()->path(), 'opti-hr/users-management/activity-logs') ? 'true' : 'false' }}">
                <i class="icofont-gear"></i>
                <span>Système</span>
                <i class="arrow icofont-rounded-down"></i>
            </a>
            <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/users-management/roles') || Str::startsWith(request()->path(), 'opti-hr/users-management/permissions') || Str::startsWith(request()->path(), 'opti-hr/users-management/activity-logs') ? 'show' : '' }}"
                id="menu-system">
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/roles') ? 'active' : '' }}"
                       href="{{ route('roles.index') }}">
                        <span>Rôles</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/permissions') ? 'active' : '' }}"
                       href="{{ route('permissions.index') }}">
                        <span>Permissions</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/activity-logs') ? 'active' : '' }}"
                       href="{{ route('activity-logs.index') }}">
                        <span>Journal d'activité</span>
                    </a>
                </li>
            </ul>
        </li>
    @endrole

    {{-- Personnel --}}
    @can('voir-un-employee')
        <li class="menu-item has-submenu {{ Str::startsWith(request()->path(), 'opti-hr/membres') ? 'expanded' : '' }}">
            <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/membres') ? 'active' : '' }}"
               data-bs-toggle="collapse"
               data-bs-target="#menu-personnel"
               href="#"
               aria-expanded="{{ Str::startsWith(request()->path(), 'opti-hr/membres') ? 'true' : 'false' }}">
                <i class="icofont-user-suited"></i>
                <span>Personnel</span>
                <i class="arrow icofont-rounded-down"></i>
            </a>
            <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/membres') ? 'show' : '' }}"
                id="menu-personnel">
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/membres/directions/list') ? 'active' : '' }}"
                       href="{{ route('directions') }}">
                        <span>Directions</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/membres/pages') ? 'active' : '' }}"
                       href="{{ route('membres.pages') }}">
                        <span>Membres</span>
                    </a>
                </li>
                @can('send-paie')
                    <li>
                        <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/membres/pay-form') ? 'active' : '' }}"
                           href="{{ route('membres.pay-form') }}">
                            <span>Bulletins de paie</span>
                        </a>
                    </li>
                @endcan
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/membres/contrats') ? 'active' : '' }}"
                       href="{{ route('contrats.index') }}">
                        <span>Contrats</span>
                    </a>
                </li>
            </ul>
        </li>
    @endcan

    {{-- Section: Mon Espace (uniquement pour les utilisateurs avec un employé associé) --}}
    @if (auth()->user()->hasEmployee() && auth()->user()->getCurrentDuty())
        <li class="menu-section">
            <span class="section-title">Mon Espace</span>
        </li>

        <li class="menu-item has-submenu {{ Str::startsWith(request()->path(), 'opti-hr/employee') ? 'expanded' : '' }}">
            <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/employee') ? 'active' : '' }}"
               data-bs-toggle="collapse"
               data-bs-target="#menu-mydata"
               href="#"
               aria-expanded="{{ Str::startsWith(request()->path(), 'opti-hr/employee') ? 'true' : 'false' }}">
                <i class="icofont-ui-user"></i>
                <span>Mes Données</span>
                <i class="arrow icofont-rounded-down"></i>
            </a>
            <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/employee') ? 'show' : '' }}"
                id="menu-mydata">
                <li>
                    <a class="ms-link {{ Request::is('opti-hr/employee/data') ? 'active' : '' }}"
                       href="{{ route('employee.data') }}">
                        <span>Mes informations</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/employee/pay') ? 'active' : '' }}"
                       href="{{ route('employee.pay', Auth::user()->employee) }}">
                        <span>Mes bulletins</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif

    {{-- Section: Support --}}
    <li class="menu-section">
        <span class="section-title">Support</span>
    </li>

    {{-- Aide --}}
    <li class="menu-item">
        <a class="m-link {{ Request::is('opti-hr/help*') ? 'active' : '' }}" href="{{ route('help.index') }}">
            <i class="icofont-question-circle"></i>
            <span>Aide</span>
        </a>
    </li>

</ul>
