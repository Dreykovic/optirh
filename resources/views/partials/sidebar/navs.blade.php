<ul class="menu-list flex-grow-1 mt-3">
    <li>
        <a class="m-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">
            <i class="icofont-home fs-5"></i>
            <span>Tableau De Bord</span>
        </a>
    </li>


    <!-- Attendances -->
    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'attendances') ? 'active' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#attendances-navs" href="#">
            <i class="icofont-calendar fs-5"></i> <span>Attendances</span> <span
                class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'attendances') ? 'show' : '' }}"
            id="attendances-navs">
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'attendances/absences/request/create') ? 'active' : '' }}"
                    href="{{ route('absences.create') }}">
                    <span>Faire Une Demande</span>
                </a>
            </li>
            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'attendances/absences/requests') ? 'active' : '' }}"
                    href="{{ route('absences.requests') }}">
                    <span>Demandes </span>
                </a>
            </li>
            @can('configurer-une-absence')
                <li>
                    <a class="ms-link {{ Request::is('attendances/absence-types/list') ? 'active' : '' }}"
                        href="{{ route('absenceTypes.index') }}">
                        <span>Types d'absences</span>
                    </a>
                </li>
            @endcan

            @can('voir-un-férié')
                <li>
                    <a class="ms-link {{ Request::is('attendances/holidays/list') ? 'active' : '' }}"
                        href="{{ route('holidays.index') }}">
                        <span>Jours Fériés</span>
                    </a>
                </li>
            @endcan



        </ul>

    </li>
    @can('voir-un-credentials')
        <!-- User Management -->
        <li class="collapsed">
            <a class="m-link {{ Str::startsWith(request()->path(), 'users-management') ? 'active' : '' }}"
                data-bs-toggle="collapse" data-bs-target="#users-management-navs" href="#">
                <i class="icofont-users-alt-2 fs-5"></i> <span>Utilisateurs</span> <span
                    class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
            <!-- Menu: Sub menu ul -->
            <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'users-management') ? 'show' : '' }}"
                id="users-management-navs">
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'users-management/credentials/list') ? 'active' : '' }}"
                        href="{{ route('credentials.index') }}">
                        <span>Identifiants</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'users-management/roles/') ? 'active' : '' }}"
                        href="{{ route('roles.index') }}">
                        <span>Roles</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'users-management/permissions/') ? 'active' : '' }}"
                        href="{{ route('permissions.index') }}">
                        <span>Permissions</span>
                    </a>
                </li>




            </ul>

        </li>
    @endcan

    @can('voir-un-employee')
        <!-- Membres -->
        <li class="collapsed">
            <a class="m-link {{ Str::startsWith(request()->path(), 'membres') ? 'active' : '' }}" data-bs-toggle="collapse"
                data-bs-target="#membres-navs" href="#">
                <i class="icofont-calendar fs-5"></i> <span>Personnel</span> <span
                    class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
            <!-- Menu: Sub menu ul -->
            <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'membres') ? 'show' : '' }}"
                id="membres-navs">
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'membres/directions/list') ? 'active' : '' }}"
                        href="{{ route('directions') }}">
                        <span>Directions</span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'membres/pages') ? 'active' : '' }}"
                        href="{{ route('membres.pages') }}">
                        <span>Membres </span>
                    </a>
                </li>

                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'membres/pay-form') ? 'active' : '' }}"
                        href="{{ route('membres.pay-form') }}">
                        <span>Factures </span>
                    </a>
                </li>
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'membres/contrats') ? 'active' : '' }}"
                        href="{{ route('contrats.index') }}">
                        <span>Contrats </span>
                    </a>
                </li>

            </ul>

        </li>
    @endcan

        <li class="collapsed">
            <a class="m-link {{ Str::startsWith(request()->path(), 'employee') ? 'active' : '' }}" data-bs-toggle="collapse"
                data-bs-target="#employees-navs" href="#">
                <i class="icofont-calendar fs-5"></i> <span>Mes Données</span> <span
                    class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
            <!-- Menu: Sub menu ul -->
            <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'employee') ? 'show' : '' }}"
                id="employees-navs">
                <li><a class="ms-link {{ Request::is('employee/data') ? 'active' : '' }}" href="{{ route('employee.data') }}">
                <span>informations</span></a>
                </li>
    
                <li>
                    <a class="ms-link {{ Str::startsWith(request()->path(), 'employee/pay') ? 'active' : '' }}"
                        href="{{ route('employee.pay',Auth::user()->employee) }}">
                        <span>Factures </span>
                    </a>
                </li>

            </ul>

        </li>


          <!-- Help -->
          <li>
            <a class="ms-link {{ Request::is('help') ? 'active' : '' }}" href="{{ route('help') }}">
                <i class="icofont-info fs-5"></i>
                <span>Aide</span>
            </a>
        </li>


</ul>
