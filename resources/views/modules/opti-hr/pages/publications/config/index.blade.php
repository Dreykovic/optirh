@extends('modules.opti-hr.pages.base')

@section('admin-content')
    <div class="row g-0">
        <div class="col-12">
            <!-- Main Card -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <!-- Chat Header -->
                <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar-wrapper position-relative">
                            <img class="avatar rounded-circle" src="{{ asset('assets/images/xs/avatar2.jpg') }}"
                                alt="Collaborative space icon" width="48" height="48">
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1"
                                style="width: 12px; height: 12px;" aria-hidden="true" title="Active space"></span>
                        </div>
                        <div class="ms-3">
                            <h1 class="h4 mb-0 fw-bold">Espace Collaboratif</h1>
                            <p class="text-muted small mb-0">Notes et informations partagées</p>
                        </div>
                    </div>

                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm d-none d-md-inline-block"
                            id="toggleFilters" aria-label="Toggle filters">
                            <i class="icofont-filter me-1"></i>Filtrer
                        </button>
                    </div>
                </div>

                <!-- Filters (hidden by default) -->
                <div id="filterOptions" class="card-body bg-light border-bottom p-3" style="display: none;">
                    <div class="row g-2">
                        <div class="col-12 col-md-3">
                            <label for="statusFilter" class="form-label small">Statut</label>
                            <select id="statusFilter" class="form-select form-select-sm">
                                <option value="all">Tous</option>
                                <option value="published">Publiés</option>
                                <option value="pending">En attente</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="dateFilter" class="form-label small">Date</label>
                            <select id="dateFilter" class="form-select form-select-sm">
                                <option value="all">Toutes dates</option>
                                <option value="today">Aujourd'hui</option>
                                <option value="week">Cette semaine</option>
                                <option value="month">Ce mois</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="searchPublications" class="form-label small">Rechercher</label>
                            <input type="search" id="searchPublications" class="form-control form-control-sm"
                                placeholder="Rechercher par titre ou contenu">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-primary w-100">Appliquer</button>
                        </div>
                    </div>
                </div>

                <!-- Chat Body -->
                <div class="card-body p-0">
                    <div class="chat-container position-relative" style="height: calc(100vh - 350px); overflow-y: auto;">
                        <ul class="chat-history list-unstyled mb-0 p-4" id="chatHistory">
                            <!-- Message Timeline Indicator -->
                            <li class="timeline-marker text-center my-4 position-relative">
                                <span
                                    class="badge bg-light text-dark px-3 py-2 shadow-sm position-relative">Aujourd'hui</span>
                                <hr class="position-absolute top-50 start-0 end-0 m-0" style="z-index: -1;">
                            </li>
                            @include('modules.opti-hr.pages.publications.config.items')


                            <!-- Load More Button -->
                            <li class="text-center my-4" id="loadMoreContainer">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="loadMoreBtn">
                                    <i class="icofont-refresh me-1"></i>Charger plus
                                </button>
                            </li>
                        </ul>

                        <!-- Scroll to Bottom Button -->
                        <button type="button" id="scrollToBottomBtn"
                            class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 mb-4 me-4"
                            style="width: 40px; height: 40px; display: none;" aria-label="Défiler vers le bas">
                            <i class="icofont-arrow-down"></i>
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
    <script src="{{ asset('app-js/crud/post.js') }}"></script>
    <script src="{{ asset('app-js/crud/put.js') }}"></script>
    <script src="{{ asset('app-js/crud/delete.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle filters
            const toggleFiltersBtn = document.getElementById('toggleFilters');
            const filterOptions = document.getElementById('filterOptions');

            if (toggleFiltersBtn && filterOptions) {
                toggleFiltersBtn.addEventListener('click', function() {
                    const isHidden = filterOptions.style.display === 'none';
                    filterOptions.style.display = isHidden ? 'block' : 'none';
                    toggleFiltersBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');

                    // Announce to screen readers
                    document.getElementById('a11yAnnouncer').textContent = isHidden ? 'Filtres affichés' :
                        'Filtres masqués';
                });
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

            // Enhance file input user experience
            const fileInput = document.getElementById('file');
            const fileList = document.getElementById('fileList');

            if (fileInput && fileList) {
                fileInput.addEventListener('change', function() {
                    fileList.innerHTML = '';

                    if (this.files.length > 0) {
                        fileList.style.display = 'flex';

                        Array.from(this.files).forEach((file, index) => {
                            const fileType = file.type;
                            const fileItem = document.createElement('div');
                            fileItem.classList.add('file-item', 'd-flex', 'align-items-center',
                                'p-2', 'border', 'rounded');

                            // Icon based on file type
                            const icon = document.createElement('i');
                            icon.classList.add('me-2');

                            if (fileType === "application/pdf") {
                                icon.classList.add('icofont-file-pdf', 'text-danger');
                            } else if (fileType.startsWith("image/")) {
                                icon.classList.add('icofont-image', 'text-success');
                            } else {
                                icon.classList.add('icofont-file-alt', 'text-warning');
                            }

                            // Filename
                            const fileName = document.createElement('span');
                            fileName.textContent = file.name;
                            fileName.classList.add('small', 'text-truncate');
                            fileName.style.maxWidth = '150px';

                            // Remove button
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.classList.add('btn', 'btn-sm', 'text-danger', 'ms-2');
                            removeBtn.innerHTML = '<i class="icofont-close-line"></i>';
                            removeBtn.setAttribute('aria-label', 'Supprimer ' + file.name);
                            removeBtn.dataset.fileIndex = index;

                            removeBtn.addEventListener('click', function() {
                                fileItem.remove();

                                // Check if all files removed
                                if (fileList.children.length === 0) {
                                    fileList.style.display = 'none';
                                }
                            });

                            fileItem.appendChild(icon);
                            fileItem.appendChild(fileName);
                            fileItem.appendChild(removeBtn);
                            fileList.appendChild(fileItem);
                        });
                    } else {
                        fileList.style.display = 'none';
                    }
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
