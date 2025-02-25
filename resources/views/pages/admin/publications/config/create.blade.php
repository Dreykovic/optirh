<div class="card mb-2">
    <div class="card-body">

        <div class="post">
            <form id="modelAddForm" class="" data-model-add-url="{{ route('publications.config.save') }}">
                @csrf





                <div class="mb-3">
                    <label for="title" class="form-label required">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value=""
                        placeholder="titre de la note ">
                </div>


                <div class="mb-3">
                    <label for="content" class="form-label">Message </label>
                    <textarea class="form-control" id="content" rows="3" name="content"></textarea>
                </div>
                <div class="py-3">



                    <label for="file" role="button" class="form-label px-3 btn">
                        <i class="icofont-upload"></i>
                    </label>
                    <input type="file" class="d-none" id="file" name="file"
                        accept="image/*, application/pdf">
                    <i class="icofont-file-pdf fs-5 text-danger"></i>
                    <span id="fileName" class="ms-2 text-muted"></span> <!-- Zone d'affichage du nom du fichier -->

                    <button type="submit" class="btn btn-primary float-sm-end  mt-2 mt-sm-0" atl="Publier une note"
                        id="modelAddBtn" data-bs-dismiss="modal">
                        <span class="normal-status">
                            Publier
                        </span>
                        <span class="indicateur d-none">
                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Un Instant...
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
