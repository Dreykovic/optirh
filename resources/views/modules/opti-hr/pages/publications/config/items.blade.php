@forelse ($publications as $publication)
    <li class="publication-item mb-4 d-flex {{ $publication->author_id === auth()->user()->id ? 'flex-row-reverse' : 'flex-row' }} align-items-start 
               {{ $publication->author_id !== auth()->user()->id && $publication->status === 'pending' ? 'd-none' : '' }}
               {{ $publication->status === 'published' ? 'publication-published' : 'publication-pending' }} shadow-hover"
        data-id="{{ $publication->id }}" data-status="{{ $publication->status }}"
        data-date="{{ $publication->created_at }}">

        <!-- Avatar avec initiales améliorées -->
        <div class="{{ $publication->author_id === auth()->user()->id ? 'ms-3' : 'me-3' }}">
            <div class="avatar-wrapper position-relative">
                <div class="avatar rounded-circle d-flex align-items-center justify-content-center {{ $publication->author_id === auth()->user()->id ? 'bg-primary text-white' : 'bg-secondary bg-opacity-10' }}"
                    style="width: 48px; height: 48px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.2s ease;">
                    <span class="fw-bold">{{ strtoupper(substr($publication->author->username ?? 'U', 0, 1)) }}</span>
                </div>
                @if ($publication->status === 'pending')
                    <span class="position-absolute bottom-0 end-0 bg-warning rounded-circle border border-white"
                        style="width: 12px; height: 12px;" aria-hidden="true" title="En attente de publication"></span>
                @endif
            </div>
        </div>

        <!-- Contenu du message avec meilleur design -->
        <div class="publication-content {{ $publication->author_id === auth()->user()->id ? 'own-message' : 'other-message' }}"
            style="max-width: 80%; transition: transform 0.2s ease;">
            <!-- En-tête du message -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="user-info">
                    <span class="fw-semibold">{{ $publication->author->username ?? 'Utilisateur' }}</span>
                    <span class="text-muted ms-2 message-time small" title="{{ $publication->created_at }}">
                        <i class="icofont-clock-time"></i>
                        @formatDate($publication->created_at)
                    </span>
                </div>

                @if ($publication->status === 'pending')
                    <span class="badge bg-warning text-dark small rounded-pill px-3">En attente</span>
                @endif
            </div>

            <!-- Corps du message avec ombre et coins arrondis -->
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div
                    class="card-header py-2 px-3 {{ $publication->author_id === auth()->user()->id ? 'bg-primary bg-gradient text-white' : 'bg-light' }}">
                    <h2 class="h6 mb-0 fw-bold model-value">{{ $publication->title }}</h2>
                </div>
                <div class="card-body p-3">
                    <div class="message-text mb-3 text-break">
                        {!! nl2br(e($publication->content)) !!}
                    </div>

                    <!-- Fichiers joints avec design amélioré -->
                    @if ($publication->files->isNotEmpty())
                        <div class="attached-files border-top pt-3">
                            <p class="text-muted small mb-2 d-flex align-items-center">
                                <i class="icofont-paper-clip me-1"></i>
                                <span>Fichiers ({{ $publication->files->count() }})</span>
                            </p>

                            <div class="row g-2">
                                @foreach ($publication->files as $file)
                                    <div class="col-12 col-md-6">
                                        <a href="#"
                                            class="file-item d-flex align-items-center p-2 rounded-3 border downloadBtn text-decoration-none text-reset hover-shadow transition"
                                            data-publication-id="{{ $file->id }}"
                                            aria-label="Télécharger {{ $file->display_name }}">
                                            <div
                                                class="file-icon d-flex align-items-center justify-content-center rounded-circle me-2 p-2 
                                            {{ strpos($file->mime_type, 'pdf') !== false ? 'bg-danger bg-opacity-10' : (strpos($file->mime_type, 'image') !== false ? 'bg-success bg-opacity-10' : 'bg-warning bg-opacity-10') }}">
                                                <i
                                                    class="fs-5 {{ strpos($file->mime_type, 'pdf') !== false ? 'icofont-file-pdf text-danger' : (strpos($file->mime_type, 'image') !== false ? 'icofont-image text-success' : 'icofont-file-alt text-warning') }}"></i>
                                            </div>
                                            <div class="file-info overflow-hidden flex-grow-1">
                                                <p class="file-name mb-0 text-truncate small">
                                                    {{ $file->display_name }}</p>
                                            </div>
                                            <i class="icofont-download ms-2 text-primary"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions avec boutons améliorés -->
            @can('configurer-une-publication')
                <div class="publication-actions mt-2 text-end">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light rounded-pill shadow-sm dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false" aria-label="Options">
                            <i class="icofont-gear"></i> Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 py-1">
                            <li>
                                <div class="modelUpdateFormContainer dropdown-item py-2 rounded"
                                    id="publicationUpdateForm{{ $publication->id }}">
                                    <form
                                        data-model-update-url="{{ route('publications.config.updateStatus', [$publication->status === 'published' ? 'pending' : 'published', $publication->id]) }}">
                                        <a role="button" class="modelUpdateBtn d-flex align-items-center"
                                            alt="update status">
                                            <span class="normal-status d-flex align-items-center">
                                                <i
                                                    class="icofont-{{ $publication->status === 'published' ? 'eye-blocked' : 'check' }} {{ $publication->status === 'published' ? 'text-warning' : 'text-success' }} me-2"></i>
                                                <span>{{ $publication->status === 'published' ? 'Cacher' : 'Publier' }}</span>
                                            </span>
                                            <span class="indicateur d-none">
                                                <span class="spinner-grow spinner-grow-sm me-2" role="status"
                                                    aria-hidden="true"></span>
                                                Traitement...
                                            </span>
                                        </a>
                                    </form>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <button class="dropdown-item py-2 modelDeleteBtn d-flex align-items-center"
                                    data-model-action="delete"
                                    data-model-delete-url="{{ route('publications.config.destroy', $publication->id) }}"
                                    data-model-parent-selector=".publication-item">
                                    <span class="normal-status d-flex align-items-center">
                                        <i class="icofont-ui-delete text-danger me-2"></i>
                                        <span>Supprimer</span>
                                    </span>
                                    <span class="indicateur d-none">
                                        <span class="spinner-grow spinner-grow-sm me-2" role="status"
                                            aria-hidden="true"></span>
                                        <span>Suppression...</span>
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
    <li class="text-center p-5 bg-light bg-opacity-50 rounded-4 my-4">
        <div class="empty-state py-4">
            <div class="icon-container mb-3">
                <i class="icofont-chat fs-1 text-muted opacity-50"></i>
            </div>
            <h3 class="h5 fw-light text-muted">Aucune publication</h3>
            <p class="text-muted small">Soyez le premier à partager une information avec l'équipe.</p>
            <button class="btn btn-sm btn-primary rounded-pill mt-2">
                <i class="icofont-plus-circle me-1"></i> Nouvelle publication
            </button>
        </div>
    </li>
@endforelse
