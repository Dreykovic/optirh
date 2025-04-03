

    const employeeId = document.getElementById('employeeId').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const paginator = new Paginator({
        apiUrl: `/opti-hr/api/files/${employeeId}`, // URL de l'API
            renderElement: document.getElementById('fileList'), // Élément où afficher les données
            renderCallback: renderFiles, // Fonction pour rendre les fichiers
            searchInput: document.getElementById('searchInput'), // Input de recherche
            directorInput:document.getElementById('directorInput'),
            limitSelect: document.getElementById('limitSelect'), // Sélecteur de limite
            paginationElement: document.getElementById('pagination'), // Élément pour la pagination
        
        });

        // Fonction de rendu pour les fichiers
        function renderFiles(files) {
            const fileList = document.getElementById('fileList');
            fileList.innerHTML = '';

            if (files.length === 0) {
                fileList.innerHTML = '<div class="alert alert-warning">Aucun fichier trouvé.</div>';
            } else {
                files.forEach(file => {
                    const fileElement = document.createElement('div');
                    fileElement.className = 'py-2 d-flex align-items-center border-bottom';
                    fileElement.innerHTML = `
                        <div class="d-flex ms-3 align-items-center flex-fill">
                            <span class="avatar small-11 ${file.icon_class} rounded-circle text-center d-flex align-items-center justify-content-center">
                                <i class="${file.icon} fs-5"></i>
                            </span>
                            <div class="d-flex flex-column ps-3" style="max-width: 400px;">
                                <h6 class="fw-bold mb-0 small-14 text-truncate text-muted" title="${file.display_name}">
                                    ${file.display_name}
                                </h6>
                            </div>
                        </div>
                        <div class="btn-group">
                            <i class="bi bi-three-dots-vertical" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
                            <ul class="dropdown-menu border-0 shadow bg-primary">                               
                                <li>
                                    <form action="/opti-hr/files/delete/${file.id}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <button type="submit" class="dropdown-item text-light">Supprimer</button>
                                    </form>
                                </li>
                                <li><a class="dropdown-item text-light" href="${file.url}" target="_blank">Ouvrir</a></li>
                            </ul>
                        </div>

                        <div class="modal fade" id="updateFileModal${file.id}" tabindex="-1" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    
                                    <div class="modal-body modelUpdateFormContainer" id="updateFileForm${file.id}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="">Modifier Document</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form data-model-update-url="/opti-hr/files/rename/${file.id}">
                                            @csrf
                                                <input type="hidden" name="_method" value="PUT">
                                            <div class="">
                                                <label for="files" class="form-label">Nouveau nom du fichier:</label>
                                                <input type="text" value="${file.display_name} ${file.id}" name="new_name" id="files" class="form-control form-control">
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary  modelUpdateBtn" atl="Modifier Absence Type"
                                                    data-bs-dismiss="modal">
                                                    <span class="normal-status">
                                                        Enregistrer4
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
                        </div>
                    `;
                    fileList.appendChild(fileElement);
                });
            }
        }



// <li><a data-bs-target="#updateFileModal${file.id}" data-bs-toggle="modal" class="dropdown-item text-light" ><i class="icofont-edit text-success m-2"></i>Renommer</a></li>
