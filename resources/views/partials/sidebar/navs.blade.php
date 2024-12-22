<ul class="menu-list flex-grow-1 mt-3">
    <li><a class="m-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}"><i
                class="icofont-home fs-5"></i> <span>Tableau De Bord</span></a>
    </li>


    <!-- Accounte -->
    {{-- <li class="collapsed">
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

    </li> --}}


    <!-- Membres -->
    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'membres') ? 'active' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#membres-navs" href="#">
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
                <a class="ms-link {{ Str::startsWith(request()->path(), '/membres/list') ? 'active' : '' }}"
                    href="{{ route('membres') }}">
                    <span>Membres </span>
                </a>
            </li>
          
        </ul>

    </li>




    <!-- Help -->
    <li><a class="ms-link {{ Request::is('help') ? 'active' : '' }}" href="{{ route('help') }}"><i
                class="icofont-home fs-5"></i>
            <span>Aide</span></a>
    </li>




</ul>
