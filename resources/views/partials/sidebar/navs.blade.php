<ul class="menu-list flex-grow-1 mt-3">
    <li><a class="m-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}"><i
                class="icofont-home fs-5"></i> <span>Tableau De Bord</span></a>
    </li>
    <!-- Clients -->

    <li><a class="m-link {{ Request::is('clients/list') ? 'active' : '' }}" href="{{ route('clients.index') }}"><i
                class="icofont-home fs-5"></i> <span>Liste Clients</span></a>
    </li>


    <!-- Accounte -->
    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'accounts') ? 'active' : '' }}" data-bs-toggle="collapse"
            data-bs-target="#accounts-navs" href="#">
            <i class="icofont-home fs-5"></i> <span>Comptes</span> <span
                class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'accounts') ? 'show' : '' }}"
            id="accounts-navs">

            <li><a class="ms-link {{ Request::is('accounts/list') ? 'active' : '' }}"
                    href="{{ route('accounts.index') }}">
                    <span>Liste Comptes</span></a>
            </li>
            <li><a class="ms-link {{ Request::is('resuest') ? 'active' : '' }}" href="#">
                    <span>Calcul d'intérets </span></a>
            </li>

            <li><a class="ms-link {{ Request::is('accounts/types/list') ? 'active' : '' }}"
                    href="{{ route('accounts.types.index') }}">
                    <span>Types Comptes</span></a>
            </li>
        </ul>

    </li>



    <!-- Transaction -->
    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'transactions') ? 'active' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#transactions-navs" href="#">
            <i class="icofont-home fs-5"></i>
            <span>Transactions</span>
            <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span>
        </a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'transactions') ? 'show' : '' }}"
            id="transactions-navs">

            <li><a class="ms-link {{ Request::is('transactions/history') ? 'active' : '' }}"
                    href="{{ route('transactions.history') }}">
                    <span>Historique</span></a>
            </li>
            <li><a class="ms-link {{ Request::is('transactions/types/list') ? 'active' : '' }}"
                    href="{{ route('transactions.types.index') }}">
                    <span>Catégories Transactions</span></a>
            </li>



        </ul>

    </li>

    <!-- Employees -->
    <li><a class="ms-link {{ Request::is('employees/list') ? 'active' : '' }}"
            href="{{ route('employees.index') }}"><i class="icofont-home fs-5"></i>
            <span>Tous les employés</span></a>
    </li>
    <!-- Help -->
    <li><a class="ms-link {{ Request::is('help') ? 'active' : '' }}" href="{{ route('help') }}"><i
                class="icofont-home fs-5"></i>
            <span>Aide</span></a>
    </li>


    <!-- Reports -->
    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'reports') ? 'active' : '' }}" data-bs-toggle="collapse"
            data-bs-target="#report-navs" href="#">
            <i class="icofont-home fs-5"></i>
            <span>Etats</span>
            <span class="arrow icofont-dotted-down ms-auto text-end fs-5"></span>
        </a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'reports') ? 'show' : '' }}"
            id="report-navs">

            <li><a class="ms-link {{ Request::is('transactions/history') ? 'active' : '' }}"
                    href="{{ route('transactions.history') }}">
                    <span>Historique</span></a>
            </li>




        </ul>

    </li>

</ul>
