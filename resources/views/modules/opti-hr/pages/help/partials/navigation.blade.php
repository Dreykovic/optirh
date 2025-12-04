{{-- Navigation Prev/Next --}}
<nav class="help-navigation" aria-label="Navigation entre sections">
    <div class="nav-prev">
        @if($prevSection)
            <a href="{{ route($prevSection['route']) }}" class="nav-link">
                <i class="icofont-arrow-left"></i>
                <div class="nav-text">
                    <span class="nav-label">Précédent</span>
                    <span class="nav-title">{{ $prevSection['title'] }}</span>
                </div>
            </a>
        @endif
    </div>

    <div class="nav-next">
        @if($nextSection)
            <a href="{{ route($nextSection['route']) }}" class="nav-link">
                <div class="nav-text">
                    <span class="nav-label">Suivant</span>
                    <span class="nav-title">{{ $nextSection['title'] }}</span>
                </div>
                <i class="icofont-arrow-right"></i>
            </a>
        @endif
    </div>
</nav>
