<div class="sidebar px-4 py-4 py-md-5 me-0">
    <div class="d-flex flex-column h-100">
        <a href="{{ route('home') }}" class="mb-0 brand-icon">
            <span class="logo-icon">

                <img width="55" height="55" src="{{ asset('assets/img/logo.png') }}" alt="">

            </span>
            <span class="logo-text">Recours</span>
        </a>
        <!-- Menu: main ul -->

        @include('partials.sidebar.recours.navs')

        <!-- Theme: Switch Theme -->
        <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-center justify-content-center">
                <div class="form-check form-switch theme-switch">
                    <input class="form-check-input" type="checkbox" id="theme-switch">
                    <label class="form-check-label" for="theme-switch">Mode Sombre!</label>
                </div>
            </li>

        </ul>

        <!-- Menu: menu collepce btn -->
        <button type="button" class="btn btn-link sidebar-mini-btn text-light">
            <span class="ms-2"><i class="icofont-bubble-right"></i></span>
        </button>
    </div>
</div>
