<ul class="menu-list">

    {{-- Section: Principal --}}
    <li class="menu-section">
        <span class="section-title">Principal</span>
    </li>

    {{-- Dashboard --}}
    <li class="menu-item">
        <a class="m-link {{ Request::is('recours') ? 'active' : '' }}" href="{{ route('recours.home') }}">
            <i class="icofont-ui-home"></i>
            <span>Tableau de bord</span>
        </a>
    </li>

    {{-- Section: Recours --}}
    <li class="menu-section">
        <span class="section-title">Gestion</span>
    </li>

    {{-- Nouveau Recours --}}
    <li class="menu-item">
        <a class="m-link {{ Request::is('recours/new') ? 'active' : '' }}" href="{{ route('recours.new') }}">
            <i class="icofont-plus-circle"></i>
            <span>Nouveau recours</span>
        </a>
    </li>

    {{-- Liste des Recours --}}
    <li class="menu-item">
        <a class="m-link {{ Request::is('recours/index') ? 'active' : '' }}" href="{{ route('recours.index') }}">
            <i class="icofont-listing-box"></i>
            <span>Liste des recours</span>
        </a>
    </li>

    {{-- Section: Support --}}
    <li class="menu-section">
        <span class="section-title">Support</span>
    </li>

    {{-- Aide --}}
    <li class="menu-item">
        <a class="m-link {{ Request::is('recours/help') ? 'active' : '' }}" href="#">
            <i class="icofont-question-circle"></i>
            <span>Aide</span>
        </a>
    </li>

</ul>
