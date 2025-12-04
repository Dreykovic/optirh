{{-- resources/views/components/sidebar.blade.php --}}

@props([
    'appName' => 'OptiRh',
    'appRoute' => 'opti-hr.home',
    'logoPath' => 'assets/img/logo.png',
    'logoWidth' => 44,
    'logoHeight' => 44,
    'navModule' => 'opti-hr',
    'portalLink' => true,
    'darkModeSwitch' => true,
    'appSubtitle' => null,
])

<div class="sidebar px-3 py-4 me-0">
    <div class="d-flex flex-column h-100">

        {{-- Header: Logo + App Name --}}
        <div class="sidebar-header mb-4">
            <a href="{{ route($appRoute) }}" class="brand-icon text-decoration-none">
                <div class="logo-wrapper">
                    <img
                        width="{{ $logoWidth }}"
                        height="{{ $logoHeight }}"
                        src="{{ asset($logoPath) }}"
                        alt="{{ $appName }} Logo"
                        class="logo-img"
                    >
                </div>
                <div class="brand-info">
                    <span class="logo-text">{{ $appName }}</span>
                    @if($appSubtitle)
                        <span class="logo-subtitle">{{ $appSubtitle }}</span>
                    @endif
                </div>
            </a>
        </div>

        {{-- Divider --}}
        <div class="sidebar-divider mb-3"></div>

        {{-- Navigation Menu --}}
        <nav class="sidebar-nav flex-grow-1">
            @include("modules.{$navModule}.partials.sidebar.navs")
        </nav>

        {{-- Footer Section --}}
        <div class="sidebar-footer mt-auto pt-3">

            {{-- Theme Switch --}}
            @if ($darkModeSwitch)
                <div class="theme-toggle-wrapper mb-3">
                    <div class="form-check form-switch theme-switch d-flex align-items-center">
                        <input class="form-check-input" type="checkbox" id="theme-switch" role="switch">
                        <label class="form-check-label ms-2" for="theme-switch">
                            <i class="icofont-moon me-1"></i>
                            <span>Mode Sombre</span>
                        </label>
                    </div>
                </div>
            @endif

            {{-- Portal Link --}}
            @if ($portalLink)
                <a href="{{ route('gateway') }}" class="portal-link">
                    <i class="icofont-dashboard"></i>
                    <span>Portail d'Applications</span>
                </a>
            @endif

            {{-- Collapse Button --}}
            <button type="button" class="btn sidebar-mini-btn">
                <i class="icofont-rounded-double-left"></i>
            </button>
        </div>
    </div>
</div>
