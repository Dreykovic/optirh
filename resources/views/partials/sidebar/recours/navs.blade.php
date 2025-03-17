<ul class="menu-list flex-grow-1 mt-3">
    <li>
        <a class="m-link {{ Request::is('recours') ? 'active' : '' }}" href="{{ route('recours.home') }}">
            <i class="icofont-home fs-5"></i>
            <span>Tableau De Bord</span>
        </a>
    </li>

    <li>
        <a class="m-link {{ Request::is('recours/new') ? 'active' : '' }}" href="{{ route('recours.new') }}">
        <i class="icofont-plus fs-fs"></i>
            <span>Nouveau</span>
        </a>
    </li>

    <li>
        <a class="m-link {{ Request::is('recours/index') ? 'active' : '' }}" href="{{ route('recours.index') }}">
        <i class="icofont-table fs-5"></i>
            <span>Nos Recours</span>
        </a>
    </li>


    <!-- Help -->
    <li>
        <a class="ms-link {{ Request::is('help') ? 'active' : '' }}" href="">
            <i class="icofont-info fs-5"></i>
            <span>Aide</span>
        </a>
    </li>


</ul>
