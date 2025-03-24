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
                    <span class="small fw-bold">{{ substr($publication->author->username ?? 'U', 0, 1) }}</span>
                </div>
                @if ($publication->status === 'pending')
                    <span class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-1"
                        style="width: 10px; height: 10px;" aria-hidden="true" title="En attente de publication"></span>
                @endif
            </div>
        </div>

        <!-- Message Content -->
        <div class="publication-content {{ $publication->author_id === auth()->user()->id ? 'own-message' : 'other-message' }}"
            style="max-width: 75%;">
            <!-- Message Header -->
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="user-info small">
                    <span class="fw-bold">{{ $publication->author->username ?? 'Utilisateur' }}</span>
                    <span class="text-muted ms-2 message-time" title="{{ $publication->published_at }}">
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
                    <h2 class="h6 mb-0 fw-bold model-value">{{ $publication->title }}</h2>
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
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false" aria-label="Options">
                            <i class="icofont-gear"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            <li>
                                <div class="modelUpdateFormContainer dropdown-item py-2 rounded"
                                    id="publicationUpdateForm{{ $publication->id }}">

                                    <form
                                        data-model-update-url="{{ route('publications.config.updateStatus', [$publication->status === 'published' ? 'pending' : 'published', $publication->id]) }}">




                                        <a role="button" class=" modelUpdateBtn " atl="update client status">
                                            <span class="normal-status">
                                                <i class="icofont-check text-success  "></i>
                                                <span
                                                    class="d-none d-sm-none d-md-inline">{{ $publication->status === 'published' ? 'Cacher' : 'Publier' }}</span>
                                            </span>
                                            <span class="indicateur d-none">
                                                <span class="spinner-grow spinner-grow-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Un Instant...
                                            </span>
                                        </a>

                                    </form>
                                </div>

                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <button class="dropdown-item py-2 modelDeleteBtn" data-model-action="delete"
                                    data-model-delete-url="{{ route('publications.config.destroy', $publication->id) }}"
                                    data-model-parent-selector=".publication-item">
                                    <span class="normal-status">
                                        <i class="icofont-ui-delete text-danger me-2"></i>
                                        Supprimer
                                    </span>
                                    <span class="indicateur d-none">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
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
