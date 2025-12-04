<div class="card-footer bg-white p-3 border-top create-publication-footer">
    <form id="modelAddForm" data-model-add-url="{{ route('publications.config.save') }}">
        @csrf

        <!-- Publication Title -->
        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="icofont-pencil-alt-2 text-primary"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="title" name="title"
                    placeholder="Titre de votre publication" aria-label="Titre" required maxlength="255">
            </div>
            <div class="d-flex justify-content-end">
                <small class="text-muted" id="titleCharCount">0/255</small>
            </div>
        </div>

        <!-- Publication Content -->
        <div class="mb-3">
            <div class="form-floating">
                <textarea class="form-control" id="content" name="content" style="height: 100px"
                    placeholder="Partagez vos informations avec l'équipe" aria-label="Contenu"></textarea>
                <label for="content">Partagez vos informations avec l'équipe...</label>
            </div>
        </div>

        <!-- Drop Zone for Files -->
        <div class="mb-3">
            <div class="drop-zone position-relative" id="dropZone">
                <input type="file" class="file-input" id="file" name="files[]"
                    multiple accept="image/*,application/pdf"
                    aria-label="Joindre des fichiers">
                <div class="drop-zone-content text-center py-3">
                    <i class="icofont-cloud-upload d-block mb-2"></i>
                    <p class="mb-1">Glissez vos fichiers ici</p>
                    <small class="text-muted">ou cliquez pour sélectionner</small>
                </div>
            </div>
            <small class="form-text text-muted">
                <i class="icofont-info-circle"></i> Images (JPG, PNG, GIF) et PDF acceptés (max. 10 Mo par fichier)
            </small>
        </div>

        <!-- File List Preview -->
        <div id="fileList" class="file-preview mb-3" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted fw-medium">
                    <i class="icofont-paper-clip me-1"></i>
                    <span id="fileCount">0</span> fichier(s) sélectionné(s)
                </small>
                <button type="button" class="btn btn-sm btn-outline-danger" id="clearAllFiles">
                    <i class="icofont-trash me-1"></i> Tout supprimer
                </button>
            </div>
            <div id="fileListItems" class="d-flex flex-wrap gap-2">
                <!-- Files will be displayed here -->
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary px-4" id="modelAddBtn" aria-label="Publier">
                <span class="normal-status">
                    <i class="icofont-paper-plane me-1"></i>
                    Publier
                </span>
                <span class="indicateur d-none">
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Un instant...
                </span>
            </button>
        </div>
    </form>
</div>
