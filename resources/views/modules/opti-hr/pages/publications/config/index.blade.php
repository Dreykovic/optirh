@extends('modules.opti-hr.pages.base')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/publications.css') }}">
@endpush

@section('admin-content')
    <div class="row g-0">
        <div class="col-12">
            <!-- Main Card -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <!-- Chat Header -->
                <div class="card-header bg-white p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar-wrapper position-relative">
                                <div class="avatar rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                    style="width: 48px; height: 48px;">
                                    <i class="icofont-ui-messaging fs-4"></i>
                                </div>
                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white"
                                    style="width: 12px; height: 12px;" aria-hidden="true" title="Active space"></span>
                            </div>
                            <div class="ms-3">
                                <h1 class="h4 mb-0 fw-bold">Espace Collaboratif</h1>
                                <p class="text-muted small mb-0">Notes et informations partagées</p>
                            </div>
                        </div>

                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                id="toggleFilters" aria-label="Toggle filters" aria-expanded="false">
                                <i class="icofont-filter me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>

                    <!-- Navigation par onglets -->
                    @can('configurer-une-publication')
                    <nav class="nav nav-pills nav-fill gap-2" role="tablist">
                        <a class="nav-link rounded-pill {{ $status === 'all' ? 'active' : '' }}"
                           href="{{ route('publications.config.index', 'all') }}"
                           role="tab" aria-selected="{{ $status === 'all' ? 'true' : 'false' }}">
                            <i class="icofont-listing-box me-1"></i> Toutes
                            <span class="badge bg-secondary bg-opacity-25 text-dark ms-1">{{ $publications->count() }}</span>
                        </a>
                        <a class="nav-link rounded-pill {{ $status === 'published' ? 'active' : '' }}"
                           href="{{ route('publications.config.index', 'published') }}"
                           role="tab" aria-selected="{{ $status === 'published' ? 'true' : 'false' }}">
                            <i class="icofont-check-circled me-1 text-success"></i> Publiées
                        </a>
                        <a class="nav-link rounded-pill {{ $status === 'pending' ? 'active' : '' }}"
                           href="{{ route('publications.config.index', 'pending') }}"
                           role="tab" aria-selected="{{ $status === 'pending' ? 'true' : 'false' }}">
                            <i class="icofont-clock-time me-1 text-warning"></i> En attente
                        </a>
                        <a class="nav-link rounded-pill {{ $status === 'archived' ? 'active' : '' }}"
                           href="{{ route('publications.config.index', 'archived') }}"
                           role="tab" aria-selected="{{ $status === 'archived' ? 'true' : 'false' }}">
                            <i class="icofont-archive me-1 text-muted"></i> Archivées
                        </a>
                    </nav>
                    @endcan
                </div>

                <!-- Filters (hidden by default) -->
                @include('modules.opti-hr.pages.publications.config.filter')

                <!-- Chat Body -->
                <div class="card-body p-0">
                    <div class="chat-container position-relative" style="height: calc(100vh - 350px); overflow-y: auto;">
                        @include('modules.opti-hr.pages.publications.config.items')

                        <!-- Scroll to Bottom Button -->
                        <button type="button" id="scrollToBottomBtn"
                            class="btn btn-sm btn-info rounded-circle position-absolute bottom-0 end-0 mb-4 me-4"
                            style="width: 40px; height: 40px; display: none;" aria-label="Défiler vers le bas">
                            <i class="icofont-arrow-down text-primary fs-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Chat Input -->
                @can('créer-une-publication')
                    @include('modules.opti-hr.pages.publications.config.create')
                @endcan
            </div>
        </div>
    </div>

    <!-- PDF Modal -->
    @include('modules.opti-hr.pdf.overview.main')

    <!-- Accessibility Helper (Screen Reader Only) -->
    <div class="visually-hidden" aria-live="polite" id="a11yAnnouncer"></div>
@endsection

@push('js')
    <script src="{{ asset('app-js/publications/pdf.js') }}"></script>
    <script src="{{ asset('app-js/publications/create.js') }}"></script>
    <script src="{{ asset('app-js/publications/edit.js') }}"></script>
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle filters
            const toggleFiltersBtn = document.getElementById('toggleFilters');
            const filterOptions = document.getElementById('filterOptions');

            if (toggleFiltersBtn && filterOptions) {
                // Set initial state (hidden by default)
                filterOptions.style.display = 'none';

                toggleFiltersBtn.addEventListener('click', function() {
                    const isHidden = filterOptions.style.display === 'none';
                    filterOptions.style.display = isHidden ? 'block' : 'none';
                    toggleFiltersBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');

                    // Toggle button style
                    if (isHidden) {
                        toggleFiltersBtn.classList.remove('btn-outline-primary');
                        toggleFiltersBtn.classList.add('btn-primary');
                    } else {
                        toggleFiltersBtn.classList.remove('btn-primary');
                        toggleFiltersBtn.classList.add('btn-outline-primary');
                    }

                    // Announce to screen readers
                    document.getElementById('a11yAnnouncer').textContent = isHidden ? 'Filtres affichés' :
                        'Filtres masqués';
                });

                // Keep filters visible if active filters exist
                @if(($filters['date_filter'] ?? 'all') !== 'all' || !empty($filters['search'] ?? ''))
                    filterOptions.style.display = 'block';
                    toggleFiltersBtn.setAttribute('aria-expanded', 'true');
                    toggleFiltersBtn.classList.remove('btn-outline-primary');
                    toggleFiltersBtn.classList.add('btn-primary');
                @endif
            }

            // Scroll to bottom functionality
            const chatContainer = document.querySelector('.chat-container');
            const scrollToBottomBtn = document.getElementById('scrollToBottomBtn');

            if (chatContainer && scrollToBottomBtn) {
                chatContainer.addEventListener('scroll', function() {
                    // Show button when not at bottom
                    const isAtBottom = chatContainer.scrollHeight - chatContainer.scrollTop <= chatContainer
                        .clientHeight + 100;
                    scrollToBottomBtn.style.display = isAtBottom ? 'none' : 'flex';
                });

                scrollToBottomBtn.addEventListener('click', function() {
                    chatContainer.scrollTo({
                        top: chatContainer.scrollHeight,
                        behavior: 'smooth'
                    });
                });

                // Initial scroll to bottom
                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                    behavior: 'auto'
                });
            }

            // Enhanced message time formatting
            function formatDateTime() {
                document.querySelectorAll('.message-time').forEach(element => {
                    const dateString = element.getAttribute('title') || element.textContent.trim();
                    const date = new Date(dateString);
                    const now = new Date();

                    const isToday = date.getDate() === now.getDate() &&
                        date.getMonth() === now.getMonth() &&
                        date.getFullYear() === now.getFullYear();

                    const isYesterday = date.getDate() === now.getDate() - 1 &&
                        date.getMonth() === now.getMonth() &&
                        date.getFullYear() === now.getFullYear();

                    const hours = date.getHours().toString().padStart(2, '0');
                    const minutes = date.getMinutes().toString().padStart(2, '0');

                    let formattedDate;

                    if (isToday) {
                        formattedDate = `Aujourd'hui à ${hours}h${minutes}`;
                    } else if (isYesterday) {
                        formattedDate = `Hier à ${hours}h${minutes}`;
                    } else {
                        const options = {
                            weekday: 'long',
                            day: '2-digit',
                            month: 'short'
                        };
                        formattedDate =
                            `${date.toLocaleDateString('fr-FR', options)} à ${hours}h${minutes}`;
                    }

                    element.textContent = formattedDate;
                });
            }

            // Initialize time formatting
            formatDateTime();

            // Focus management for modals
            const pdfModal = document.getElementById('cont-pdf-view');
            if (pdfModal) {
                pdfModal.addEventListener('shown.bs.modal', function() {
                    // Set focus to modal or close button
                    const closeBtn = pdfModal.querySelector('.btn-close');
                    if (closeBtn) {
                        closeBtn.focus();
                    }
                });

                pdfModal.addEventListener('hidden.bs.modal', function() {
                    // Return focus to the element that opened the modal
                    const openedBy = document.activeElement;
                    if (openedBy) {
                        openedBy.focus();
                    }
                });
            }
        });
    </script>
@endpush
