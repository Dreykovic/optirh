{{-- Sidebar Navigation --}}
<aside class="help-sidebar">
    <div class="sidebar-header">
        <h3>Guide Utilisateur</h3>
        <p>OptiHR - ARCOP MAN</p>
    </div>

    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            @foreach($sections as $index => $section)
                @if($index === 0)
                    {{-- Accueil --}}
                    <li class="sidebar-item {{ $currentSection === $section['slug'] ? 'active' : '' }}">
                        <a href="{{ route($section['route']) }}">
                            <i class="{{ $section['icon'] }}"></i>
                            <span>{{ $section['title'] }}</span>
                        </a>
                    </li>
                    <li class="sidebar-divider"></li>
                @else
                    <li class="sidebar-item {{ $currentSection === $section['slug'] ? 'active' : '' }}">
                        <a href="{{ route($section['route']) }}">
                            <span class="section-number">{{ $index }}</span>
                            <i class="{{ $section['icon'] }}"></i>
                            <span>{{ $section['title'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('opti-hr.dashboard') }}" class="back-to-app">
            <i class="icofont-arrow-left"></i>
            <span>Retour à l'application</span>
        </a>
    </div>
</aside>
