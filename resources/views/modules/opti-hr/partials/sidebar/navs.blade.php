<ul class="menu-list flex-grow-1 mt-3">
    <li>
        <a class="m-link {{ Request::is('opti-hr') ? 'active' : '' }}" href="{{ route('opti-hr.home') }}">
            <i class="icofont-home fs-5"></i>
            <span>Tableau De Bord</span>
        </a>
    </li>
    <li>
        <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/publications/config/list') ? 'active' : '' }}"
            href="{{ route('publications.config.index') }}">
            <i class="icofont-newspaper fs-5"></i>
            <span>Espace Collaboratif</span>
        </a>
    </li>

    <!-- Attendances -->
    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/attendances') ? 'active' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#attendances-navs" href="#">
            <i class="icofont-calendar fs-5"></i> <span>Absences</span> <span
                class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/attendances') ? 'show' : '' }}"
            id="attendances-navs">
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/attendances/absences/request/create') ? 'active' : '' }}"
                    href="{{ route('absences.create') }}">
                    <span>Soumettre Une Demande</span>
                </a>
            </li>
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/attendances/absences/requests') ? 'active' : '' }}"
                    href="{{ route('absences.requests') }}">
                    <span>Liste </span>
                </a>
            </li>
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
                    <a class="ms-link {{ Request::is('opti-hr/attendances/holidays/list') ? 'active' : '' }}"
                        href="{{ route('holidays.index') }}">
                        <span>Jours Fériés</span>
                    </a>
                </li>
            @endcan
            @can('configurer-une-absence')
                <li>
                    <a class="ms-link {{ Request::is('opti-hr/attendances/annual-decisions/view') ? 'active' : '' }}"
                        href="{{ route('decisions.show') }}">
                        <span>Décision Courante</span>
                    </a>
                </li>
            @endcan



        </ul>

    </li>
    <!-- Documents -->

    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/documents') ? 'active' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#documents-navs" href="#">
            <i class="icofont-file-document fs-5"></i> <span>Documents</span> <span
                class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/documents') ? 'show' : '' }}"
            id="documents-navs">
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/documents/requests/create') ? 'active' : '' }}"
                    href="{{ route('documents.create') }}">
                    <span>Faire Une Demande</span>
                </a>
            </li>
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/documents/requests/index') ? 'active' : '' }}"
                    href="{{ route('documents.requests') }}">
                    <span>Documents </span>
                </a>
            </li>
            @can('configurer-un-document')
                <li>
                    <a class="ms-link {{ Request::is('opti-hr/documents/document-types/list') ? 'active' : '' }}"
                        href="{{ route('documentTypes.index') }}">
                        <span>Types de Document</span>
                    </a>
                </li>
            @endcan





        </ul>

    </li>


    <!-- User Management -->

    @can('voir-un-credentials')
        <!-- User Management -->
        <li class="collapsed">
            <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management') ? 'active' : '' }}"
                data-bs-toggle="collapse" data-bs-target="#users-management-navs" href="#">
                <i class="icofont-ui-user-group fs-5"></i>
                <span>Utilisateurs</span> <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
            <!-- Menu: Sub menu ul -->
            <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/users-management') ? 'show' : '' }}"
                id="users-management-navs">
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/credentials/list') ? 'active' : '' }}"
                        href="{{ route('credentials.index') }}">
                        <span>Identifiants</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/roles/') ? 'active' : '' }}"
                        href="{{ route('roles.index') }}">
                        <span>Roles</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/permissions/') ? 'active' : '' }}"
                        href="{{ route('permissions.index') }}">
                        <span>Permissions</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/users-management/activity-logs') ? 'active' : '' }}"
                        href="{{ route('activity-logs.index') }}">
                        <span>Journal Des Actions</span>
                    </a>
                </li>




            </ul>

        </li>
    @endcan
    <!-- Employees -->

    @can('voir-un-employee')
        <!-- Membres -->
        <li class="collapsed">
            <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/membres') ? 'active' : '' }}"
                data-bs-toggle="collapse" data-bs-target="#membres-navs" href="#">
                <i class="icofont-user-suited fs-5"></i> <span>Personnel</span> <span
                    class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
            <!-- Menu: Sub menu ul -->
            <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/membres') ? 'show' : '' }}"
                id="membres-navs">
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/membres/directions/list') ? 'active' : '' }}"
                        href="{{ route('directions') }}">
                        <span>Directions</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/membres/pages') ? 'active' : '' }}"
                        href="{{ route('membres.pages') }}">
                        <span>Membres </span>
                    </a>
                </li>

                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/membres/pay-form') ? 'active' : '' }}"
                        href="{{ route('membres.pay-form') }}">
                        <span>Envoi de bulletins de paie </span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/membres/contrats') ? 'active' : '' }}"
                        href="{{ route('contrats.index') }}">
                        <span>Contrats </span>
                    </a>
                </li>

            </ul>

        </li>
    @endcan
    <!-- Data -->

    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'opti-hr/employee') ? 'active' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#employees-navs" href="#">
            <i class="icofont-files-stack fs-5"></i> <span>Mes Données</span> <span
                class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'opti-hr/employee') ? 'show' : '' }}"
            id="employees-navs">
            <li><a class="ms-link {{ Request::is('opti-hr/employee/data') ? 'active' : '' }}"
                    href="{{ route('employee.data') }}">
                    <span>Mes informations</span></a>
            </li>

            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'opti-hr/employee/pay') ? 'active' : '' }}"
                    href="{{ route('employee.pay', Auth::user()->employee) }}">
                    <span>Mes bulletins de paie </span>
                </a>
            </li>
        </ul>

    </li>


    <!-- Help -->
    <li>
        <a class="ms-link {{ Request::is('opti-hr/help') ? 'active' : '' }}" href="{{ route('help') }}">
            <i class="icofont-info fs-5"></i>
            <span>Aide</span>
        </a>
    </li>


</ul>
