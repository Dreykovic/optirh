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

                            @forelse ($publications as $publication)
                                <li class="publication-item mb-4 d-flex {{ $publication->author_id === auth()->user()->id ? 'flex-row-reverse' : 'flex-row' }} align-items-start 
                                               {{ $publication->author_id !== auth()->user()->id && $publication->status === 'pending' ? 'd-none' : '' }}
                                               {{ $publication->status === 'published' ? 'publication-published' : 'publication-pending' }}"
                                    data-id="{{ $publication->id }}" data-status="{{ $publication->status }}"
                                    data-date="{{ $publication->published_at }}">

                                    <!-- Avatar or Icon -->
                                    <div class="{{ $publication->author_id === auth()->user()->id ? 'ms-3' : 'me-3' }}">
                                        <div class="avatar-wrapper position-relative">
                                            <div class="avatar rounded-circle d-flex align-items-center justify-content-center {{ $publication->author_id === auth()->user()->id ? 'bg-primary text-white' : 'bg-light' }}"
                                                style="width: 40px; height: 40px;">
                                                <span
                                                    class="small fw-bold">{{ substr($publication->author->name ?? 'U', 0, 1) }}</span>
                                            </div>
                                            @if ($publication->status === 'pending')
                                                <span class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-1"
                                                    style="width: 10px; height: 10px;" aria-hidden="true"
                                                    title="En attente de publication"></span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Message Content -->
                                    <div class="publication-content {{ $publication->author_id === auth()->user()->id ? 'own-message' : 'other-message' }}"
                                        style="max-width: 75%;">
                                        <!-- Message Header -->
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="user-info small">
                                                <span
                                                    class="fw-bold">{{ $publication->author->name ?? 'Utilisateur' }}</span>
                                                <span class="text-muted ms-2 message-time"
                                                    title="{{ $publication->published_at }}">
                                                    <i class="icofont-clock-time"></i>
                                                    {{ $publication->published_at }}
                                                </span>
                                            </div>

                                            @if ($publication->status === 'pending')
                                                <span class="badge bg-warning text-dark small">Non publié</span>
                                            @endif
                                        </div>

                                        <!-- Message Body -->
                                        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                                            <div
                                                class="card-header py-2 px-3 {{ $publication->author_id === auth()->user()->id ? 'bg-primary text-white' : 'bg-light' }}">
                                                <h2 class="h6 mb-0 fw-bold">{{ $publication->title }}</h2>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="message-text mb-3">
                                                    {{ $publication->content }}
                                                </div>

                                                <!-- Attached Files -->
                                                @if ($publication->files->isNotEmpty())
                                                    <div class="attached-files border-top pt-3">
                                                        <p class="text-muted small mb-2">
                                                            <i class="icofont-paper-clip me-1"></i>
                                                            Fichiers joints ({{ $publication->files->count() }})
                                                        </p>

                                                        <div class="row g-2">
                                                            @foreach ($publication->files as $file)
                                                                <div class="col-12 col-md-6">
                                                                    <a href="#"
                                                                        class="file-item d-flex align-items-center p-2 rounded border downloadBtn text-decoration-none text-reset hover-shadow"
                                                                        data-publication-id="{{ $file->id }}"
                                                                        aria-label="Télécharger {{ $file->display_name }}">
                                                                        <div
                                                                            class="file-icon d-flex align-items-center justify-content-center rounded-circle me-2 p-2 
                                                                            {{ strpos($file->mime_type, 'pdf') !== false ? 'bg-danger-subtle' : (strpos($file->mime_type, 'image') !== false ? 'bg-success-subtle' : 'bg-warning-subtle') }}">
                                                                            <i
                                                                                class="fs-5 {{ strpos($file->mime_type, 'pdf') !== false ? 'icofont-file-pdf text-danger' : (strpos($file->mime_type, 'image') !== false ? 'icofont-image text-success' : 'icofont-file-alt text-warning') }}"></i>
                                                                        </div>
                                                                        <div class="file-info overflow-hidden">
                                                                            <p class="file-name mb-0 text-truncate small">
                                                                                {{ $file->display_name }}</p>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        @can('configurer-une-publication')
                                            <div class="publication-actions mt-2 text-end">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Options">
                                                        <i class="icofont-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                                        <li>
                                                            <div class="dropdown-item py-2">
                                                                <form
                                                                    data-model-update-url="{{ route('publications.config.updateStatus', [$publication->status === 'published' ? 'pending' : 'published', $publication->id]) }}">
                                                                    <button type="button"
                                                                        class="btn btn-sm w-100 text-start modelUpdateBtn"
                                                                        atl="Modifier le statut de publication">
                                                                        <span class="normal-status">
                                                                            <i
                                                                                class="icofont-{{ $publication->status === 'published' ? 'eye-blocked text-warning' : 'check text-success' }} me-2"></i>
                                                                            {{ $publication->status === 'published' ? 'Masquer' : 'Publier' }}
                                                                        </span>
                                                                        <span class="indicateur d-none">
                                                                            <span class="spinner-grow spinner-grow-sm"
                                                                                role="status" aria-hidden="true"></span>
                                                                            Un instant...
                                                                        </span>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider my-1">
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item py-2 modelDeleteBtn"
                                                                data-model-action="delete"
                                                                data-model-delete-url="{{ route('publications.config.destroy', $publication->id) }}"
                                                                data-model-parent-selector="li.publication-item">
                                                                <span class="normal-status">
                                                                    <i class="icofont-ui-delete text-danger me-2"></i>
                                                                    Supprimer
                                                                </span>
                                                                <span class="indicateur d-none">
                                                                    <span class="spinner-grow spinner-grow-sm" role="status"
                                                                        aria-hidden="true"></span>
                                                                </span>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>
                                </li>
                            @empty
                                <li class="text-center p-5">
                                    <div class="empty-state">
                                        <div class="icon-container mb-3">
                                            <i class="icofont-chat fs-1 text-muted"></i>
                                        </div>
                                        <h3 class="h5 text-muted">Aucune publication</h3>
                                        <p class="text-muted">Soyez le premier à partager une information avec l'équipe.
                                        </p>
                                    </div>
                                </li>
                            @endforelse

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
                    <div class="card-footer bg-white p-3 border-top">
                        <form id="modelAddForm" data-model-add-url="{{ route('publications.config.save') }}">
                            @csrf

                            <!-- Publication Title -->
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="icofont-pencil-alt-2 text-primary"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="title" name="title"
                                    placeholder="Titre de votre publication" aria-label="Titre" required>
                            </div>

                            <!-- Publication Content -->
                            <div class="mb-3">
                                <div class="form-floating">
                                    <textarea class="form-control" id="content" name="content" style="height: 100px"
                                        placeholder="Partagez vos informations avec l'équipe" aria-label="Contenu" required></textarea>
                                    <label for="content">Partagez vos informations avec l'équipe...</label>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <!-- File Attachment -->
                                <div class="file-upload position-relative mb-2 mb-md-0">
                                    <input type="file" class="file-input position-absolute opacity-0" id="file"
                                        name="files[]" multiple accept="image/*, application/pdf"
                                        style="width: 100%; height: 100%; cursor: pointer;" aria-label="Joindre des fichiers">

                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="document.getElementById('file').click();" aria-label="Joindre des fichiers">
                                        <i class="icofont-attachment me-1"></i>
                                        <span class="d-none d-md-inline">Joindre des fichiers</span>
                                    </button>

                                    <small class="form-text text-muted d-block mt-1">
                                        <i class="icofont-info-circle"></i> Images et PDF (max. 10 Mo)
                                    </small>
                                </div>

                                <!-- File List Preview -->
                                <div id="fileList" class="file-preview d-flex flex-wrap gap-2 my-2 w-100"
                                    style="display: none !important;">
                                    <!-- Files will be displayed here -->
                                </div>

                                <!-- Submit Button -->
                                <div class="submit-btn">
                                    <button type="submit" class="btn btn-primary" id="modelAddBtn" aria-label="Publier">
                                        <span class="normal-status">
                                            <i class="icofont-paper-plane me-1"></i>
                                            <span class="d-none d-md-inline">Publier</span>
                                        </span>
                                        <span class="indicateur d-none">
                                            <span class="spinner-grow spinner-grow-sm" role="status"
                                                aria-hidden="true"></span>
                                            <span class="d-none d-md-inline">Un instant...</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
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
