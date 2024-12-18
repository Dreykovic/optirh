<ul class="menu-list flex-grow-1 mt-3">
    <li><a class="m-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}"><i
                class="icofont-home fs-5"></i> <span>Tableau De Bord</span></a>
    </li>


    <!-- Accounte -->
    <li class="collapsed">
        <a class="m-link {{ Str::startsWith(request()->path(), 'attendances') ? 'active' : '' }}"
            data-bs-toggle="collapse" data-bs-target="#attendances-navs" href="#">
            <i class="icofont-home fs-5"></i> <span>Attendances</span> <span
                class="arrow icofont-dotted-down ms-auto text-end fs-5"></span></a>
        <!-- Menu: Sub menu ul -->
        <ul class="sub-menu collapse {{ Str::startsWith(request()->path(), 'attendances') ? 'show' : '' }}"
            id="attendances-navs">

            <li><a class="ms-link {{ Str::startsWith(request()->path(), 'attendances/absences/requests') ? 'active' : '' }}"
                    href="{{ route('absences.requests') }}">
                    <span>Demandes Absences</span></a>
            </li>
            <li><a class="ms-link {{ Request::is('attendances/absence-types/list') ? 'active' : '' }}"
                    href="{{ route('absenceTypes.index') }}">
                    <span>Types d'absences</span></a>
            </li>



        </ul>

    </li>


    <!-- Help -->
    <li><a class="ms-link {{ Request::is('help') ? 'active' : '' }}" href="{{ route('help') }}"><i
                class="icofont-home fs-5"></i>
            <span>Aide</span></a>
    </li>




</ul>
