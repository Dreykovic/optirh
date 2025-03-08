<ul class="menu-list flex-grow-1 mt-3">
    <li>
        <a class="m-link {{ Request::is('/recours/') ? 'active' : '' }}" href="{{ route('recours.home') }}">
            <i class="icofont-home fs-5"></i>
            <span>Tableau De Bord</span>
        </a>
    </li>

    <li>
        <a class="m-link {{ Request::is('/recours/') ? 'active' : '' }}" href="{{ route('recours.new') }}">
        <i class="icofont-plus fs-fs"></i>
            <span>Nouveau</span>
        </a>
    </li>

    <li>
        <a class="m-link {{ Request::is('/recours/') ? 'active' : '' }}" href="{{ route('recours.index') }}">
        <i class="icofont-table fs-5"></i>
            <span>Nos Recours</span>
        </a>
    </li>


    <!-- Data -->

    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'employee') ? 'active' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#employees-navs" href="#">
            <i class="icofont-files-stack fs-5"></i> <span>Mes Données</span> <span
                class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'employee') ? 'show' : '' }}"
            id="employees-navs">
            <li><a class="ms-link {{ Request::is('employee/data') ? 'active' : '' }}"
                    href="{{ route('employee.data') }}">
                    <span>Mes informations</span></a>
            </li>

            <li>
                <a class="ms-link {{ Str::startsWith(request()->path(), 'employee/pay') ? 'active' : '' }}"
                    href="{{ route('employee.pay', Auth::user()->employee) }}">
                    <span>Mes bulletins de paie </span>
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
