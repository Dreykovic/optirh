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
                <input type="file" class="file-input position-absolute opacity-0" id="file" name="files[]"
                    multiple accept="image/*, application/pdf" style="width: 100%; height: 100%; cursor: pointer;"
                    aria-label="Joindre des fichiers">

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
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        <span class="d-none d-md-inline">Un instant...</span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
