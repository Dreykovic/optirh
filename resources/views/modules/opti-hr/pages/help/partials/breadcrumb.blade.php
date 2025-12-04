{{-- Breadcrumb Component --}}
<nav class="help-breadcrumb" aria-label="Fil d'Ariane">
    <div class="breadcrumb-container">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Menu">
            <i class="icofont-navigation-menu"></i>
        </button>

        <ol class="breadcrumb-list">
            <li class="breadcrumb-item">
                <a href="{{ route('help.index') }}">
                    <i class="icofont-question-circle"></i>
                    <span>Aide</span>
                </a>
            </li>
            @if(!($isIndex ?? false))
            <li class="breadcrumb-separator">
                <i class="icofont-thin-right"></i>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <span>{{ $currentTitle }}</span>
            </li>
            @endif
        </ol>
    </div>
</nav>
