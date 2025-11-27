<!-- Edit Publication Modal -->
<div class="modal fade" id="publicationEdit{{ $publication->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable modelUpdateFormContainer"
        id="publicationUpdateForm{{ $publication->id }}">
        <form data-model-update-url="{{ route('publications.config.update', $publication->id) }}">
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

                    @if($publication->files->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label text-muted small">
                            <i class="icofont-paper-clip me-1"></i>
                            Fichiers attachés ({{ $publication->files->count() }})
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($publication->files as $file)
                            <span class="badge bg-light text-dark border">
                                <i class="{{ strpos($file->mime_type, 'pdf') !== false ? 'icofont-file-pdf text-danger' : 'icofont-image text-success' }} me-1"></i>
                                {{ Str::limit($file->display_name, 20) }}
                            </span>
                            @endforeach
                        </div>
                        <div class="form-text text-muted small">
                            <i class="icofont-info-circle"></i> Les fichiers ne peuvent pas être modifiés ici.
                        </div>
                    </div>
                    @endif
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
