{{-- resources/views/components/sidebar.blade.php --}}

@props([
    'appName' => 'OptiRh',
    'appRoute' => 'opti-hr.home',
    'logoPath' => 'assets/img/logo.png',
    'logoWidth' => 55,
    'logoHeight' => 55,
    'navModule' => 'opti-hr',
    'portalLink' => true,
    'darkModeSwitch' => true,
])

<div class="sidebar px-4 py-4 py-md-5 me-0">
    <div class="d-flex flex-column h-100">
        <a href="{{ route($appRoute) }}" class="mb-0 brand-icon">
            <span class="logo-icon">
                <img width="{{ $logoWidth }}" height="{{ $logoHeight }}" src="{{ asset($logoPath) }}"
                    alt="{{ $appName }} Logo">
            </span>
            <span class="logo-text">{{ $appName }}</span>
        </a>

        <!-- Menu: main ul -->
        @include("modules.{$navModule}.partials.sidebar.navs")

        <!-- Theme: Switch Theme -->
        @if ($darkModeSwitch)
            <ul class="list-unstyled mb-0">
                <li class="d-flex align-items-center justify-content-center">
                    <div class="form-check form-switch theme-switch">
                        <input class="form-check-input" type="checkbox" id="theme-switch">
                        <label class="form-check-label" for="theme-switch">Mode Sombre!</label>
                    </div>
                </li>
            </ul>
        @endif

        <!-- Link to Portal -->
        @if ($portalLink)
            <a type="button" class="btn btn-link text-light" href="{{ route('gateway') }}">
                <span class="ms-2"><i class="icofont-dashboard me-2"></i>Portail d'Applications</span>
            </a>
        @endif

        <!-- Menu: menu collapse btn -->
        <button type="button" class="btn btn-link sidebar-mini-btn text-light">
            <span class="ms-2"><i class="icofont-bubble-right"></i></span>
        </button>
    </div>
</div>
