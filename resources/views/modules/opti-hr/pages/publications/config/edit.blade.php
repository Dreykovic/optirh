<!-- Edit Publication Modal -->
<div class="modal fade" id="publicationEdit{{ $publication->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable modelUpdateFormContainer"
        id="publicationUpdateForm{{ $publication->id }}">
        <form data-model-update-url="{{ route('publications.config.update', $publication->id) }}"
              data-publication-id="{{ $publication->id }}"
              data-http-method="PUT">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="publicationEditLabel{{ $publication->id }}">
                        <i class="icofont-edit me-2"></i>Modifier la publication
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title{{ $publication->id }}" class="form-label required">Titre</label>
                        <input type="text" class="form-control" id="title{{ $publication->id }}" name="title"
                            value="{{ $publication->title }}" required maxlength="255">
                        <div class="form-text">Maximum 255 caractères</div>
                    </div>
                    <div class="mb-3">
                        <label for="content{{ $publication->id }}" class="form-label">Contenu</label>
                        <textarea name="content" class="form-control" id="content{{ $publication->id }}"
                            cols="30" rows="5" placeholder="Contenu de la publication...">{{ trim($publication->content) }}</textarea>
                    </div>

                    <!-- Fichiers existants -->
                    @if($publication->files->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="icofont-paper-clip me-1"></i>
                            Fichiers attachés
                        </label>
                        <div class="existing-files-list d-flex flex-wrap gap-2" id="existingFiles{{ $publication->id }}">
                            @foreach($publication->files as $file)
                            <div class="existing-file-item d-flex align-items-center p-2 border rounded bg-light"
                                 data-file-id="{{ $file->id }}">
                                <i class="{{ strpos($file->mime_type, 'pdf') !== false ? 'icofont-file-pdf text-danger' : 'icofont-image text-success' }} me-2"></i>
                                <span class="file-name small text-truncate" style="max-width: 150px;" title="{{ $file->display_name }}">
                                    {{ Str::limit($file->display_name, 20) }}
                                </span>
                                <button type="button" class="btn btn-sm text-danger ms-2 remove-existing-file"
                                        data-file-id="{{ $file->id }}" title="Supprimer">
                                    <i class="icofont-close-line"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="files_to_delete" id="filesToDelete{{ $publication->id }}" value="">
                    </div>
                    @endif

                    <!-- Drop Zone pour nouveaux fichiers -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="icofont-cloud-upload me-1"></i>
                            Ajouter des fichiers
                        </label>
                        <div class="drop-zone position-relative edit-drop-zone" id="editDropZone{{ $publication->id }}">
                            <input type="file" class="file-input edit-file-input" id="editFile{{ $publication->id }}" name="files[]"
                                multiple accept="image/*,application/pdf">
                            <div class="drop-zone-content text-center py-3">
                                <i class="icofont-cloud-upload d-block mb-2 text-muted"></i>
                                <p class="mb-1 small">Glissez vos fichiers ici</p>
                                <small class="text-muted">ou cliquez pour sélectionner</small>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            <i class="icofont-info-circle"></i> Images (JPG, PNG, GIF) et PDF acceptés (max. 10 Mo par fichier)
                        </small>
                    </div>

                    <!-- Liste des nouveaux fichiers -->
                    <div id="newFileList{{ $publication->id }}" class="file-preview mb-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted fw-medium">
                                <i class="icofont-plus-circle me-1 text-success"></i>
                                <span id="newFileCount{{ $publication->id }}">0</span> nouveau(x) fichier(s)
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-danger clear-new-files"
                                    data-publication-id="{{ $publication->id }}">
                                <i class="icofont-trash me-1"></i> Tout supprimer
                            </button>
                        </div>
                        <div id="newFileListItems{{ $publication->id }}" class="d-flex flex-wrap gap-2">
                            <!-- New files will be displayed here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary modelUpdateBtn" alt="Modifier Publication">
                        <span class="normal-status">
                            <i class="icofont-check me-1"></i> Enregistrer
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un instant...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .edit-drop-zone {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .edit-drop-zone:hover,
    .edit-drop-zone.drag-over {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }

    .edit-drop-zone .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .existing-file-item {
        transition: all 0.2s ease;
    }

    .existing-file-item.removing {
        opacity: 0.5;
        text-decoration: line-through;
    }

    .existing-file-item .remove-existing-file {
        padding: 0.1rem 0.3rem;
    }
</style>
