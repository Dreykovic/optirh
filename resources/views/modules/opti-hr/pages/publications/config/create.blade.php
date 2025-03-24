<div class="card mb-2">
    <div class="card-body">
        <div class="post">
            <form id="modelAddForm" class="" data-model-add-url="{{ route('publications.config.save') }}">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label required">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value=""
                        placeholder="Saisissez un titre clair et concis" aria-describedby="titleHelp">
                    <div id="titleHelp" class="form-text text-muted">
                        <i class="icofont-info-circle"></i> Le titre apparaîtra en haut de votre publication
                    </div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Message</label>
                    <textarea class="form-control" id="content" rows="3" name="content"
                        placeholder="Partagez vos informations avec l'équipe..." aria-describedby="contentHelp"></textarea>
                    <div id="contentHelp" class="form-text text-muted">
                        <i class="icofont-bulb"></i> Exprimez-vous clairement pour une meilleure communication
                    </div>
                </div>

                <div class="py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <label for="file" role="button" class="form-label px-3 btn btn-outline-secondary">
                                <i class="icofont-upload me-1"></i> Joindre des fichiers
                            </label>
                            <input type="file" class="d-none" id="file" name="files[]"
                                accept="image/*, application/pdf" multiple>
                        </div>

                        <div id="fileHelp" class="form-text text-muted ms-3">
                            <i class="icofont-paper-clip"></i> Formats acceptés: images et PDF (max 10 Mo)
                        </div>

                        <!-- Zone d'affichage des fichiers sélectionnés avec effet visuel -->
                        <div id="fileList" class="mt-2 ms-3">
                            <!-- Les fichiers sélectionnés apparaîtront ici -->
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-end">
                        <button type="submit" class="btn btn-primary float-sm-end mt-2 mt-sm-0" atl="Publier une note"
                            id="modelAddBtn" data-bs-dismiss="modal">
                            <span class="normal-status">
                                <i class="icofont-paper-plane me-1"></i> Publier
                            </span>
                            <span class="indicateur d-none">
                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Un instant...
                            </span>
                        </button>
                        <div class="form-text text-muted mt-2">
                            <i class="icofont-eye"></i> Visible par tous les collaborateurs
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
