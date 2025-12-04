@extends('modules.opti-hr.pages.base')

@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/css/help-page.css') }}">
@endsection

@php
    // Configuration des sections d'aide
    $helpSections = [
        ['route' => 'help.index', 'slug' => 'index', 'title' => 'Accueil', 'icon' => 'icofont-home'],
        ['route' => 'help.introduction', 'slug' => 'introduction', 'title' => 'Introduction', 'icon' => 'icofont-info-circle'],
        ['route' => 'help.prise-en-main', 'slug' => 'prise-en-main', 'title' => 'Prise en main', 'icon' => 'icofont-hand'],
        ['route' => 'help.tableau-de-bord', 'slug' => 'tableau-de-bord', 'title' => 'Tableau de bord', 'icon' => 'icofont-dashboard'],
        ['route' => 'help.absences', 'slug' => 'absences', 'title' => 'Gestion des absences', 'icon' => 'icofont-calendar'],
        ['route' => 'help.documents', 'slug' => 'documents', 'title' => 'Gestion des documents', 'icon' => 'icofont-file-document'],
        ['route' => 'help.personnel', 'slug' => 'personnel', 'title' => 'Administration du personnel', 'icon' => 'icofont-users-alt-5'],
        ['route' => 'help.espace-collaboratif', 'slug' => 'espace-collaboratif', 'title' => 'Espace Collaboratif', 'icon' => 'icofont-ui-messaging'],
        ['route' => 'help.mon-espace', 'slug' => 'mon-espace', 'title' => 'Mon Espace', 'icon' => 'icofont-user'],
        ['route' => 'help.faq', 'slug' => 'faq', 'title' => 'FAQ', 'icon' => 'icofont-question-circle'],
        ['route' => 'help.problemes', 'slug' => 'problemes', 'title' => 'Résolution de problèmes', 'icon' => 'icofont-tools'],
    ];

    // Trouver l'index de la section actuelle
    $currentIndex = 0;
    foreach ($helpSections as $index => $section) {
        if ($section['slug'] === ($currentSection ?? 'index')) {
            $currentIndex = $index;
            break;
        }
    }

    $prevSection = $currentIndex > 0 ? $helpSections[$currentIndex - 1] : null;
    $nextSection = $currentIndex < count($helpSections) - 1 ? $helpSections[$currentIndex + 1] : null;
    $currentSectionData = $helpSections[$currentIndex];
@endphp

@section('admin-content')
<div class="help-page-wrapper">
    <!-- Breadcrumb -->
    @include('modules.opti-hr.pages.help.partials.breadcrumb', [
        'currentTitle' => $currentSectionData['title'],
        'isIndex' => ($currentSection ?? 'index') === 'index'
    ])

    <div class="help-layout">
        <!-- Sidebar -->
        @include('modules.opti-hr.pages.help.partials.sidebar', [
            'sections' => $helpSections,
            'currentSection' => $currentSection ?? 'index'
        ])

        <!-- Contenu Principal -->
        <main class="help-main-content">
            <div class="help-content-wrapper">
                @yield('help-content')
            </div>

            <!-- Navigation Prev/Next -->
            @include('modules.opti-hr.pages.help.partials.navigation', [
                'prevSection' => $prevSection,
                'nextSection' => $nextSection
            ])

            <!-- Footer -->
            <div class="help-footer">
                <p>&copy; {{ date('Y') }} ARCOP - OptiHR | Guide Utilisateur v1.0</p>
            </div>
        </main>
    </div>
</div>
@endsection

@push('plugins-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sidebar on mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.help-sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (sidebar && !sidebar.contains(e.target) && !sidebarToggle?.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    });
</script>
@endpush
