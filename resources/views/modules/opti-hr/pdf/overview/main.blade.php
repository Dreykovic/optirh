<!-- PDF Modal -->
<div class="modal fade" id="cont-pdf-view" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow">
            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="pdfModalLabel">Aperçu du document</h5>
                <div class="modal-actions d-flex align-items-center">
                    <div class="modal-download-actions me-2">
                        <!-- Download button will be inserted here dynamically -->
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0 bg-light">
                <!-- Loading Indicator -->
                <div id="pdf-loading" class="d-none p-5 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement en cours...</span>
                    </div>
                    <p class="mt-3 text-muted">Chargement du document...</p>
                </div>

                <!-- Error Message -->
                <div id="pdf-error" class="d-none p-5 text-center">
                    <div class="error-icon mb-3">
                        <i class="icofont-warning-alt text-danger fs-1"></i>
                    </div>
                    <h6 class="error-title mb-2">Impossible de charger le document</h6>
                    <p class="error-message text-muted">Une erreur s'est produite lors du chargement du fichier.</p>
                    <button type="button" class="btn btn-outline-secondary mt-3" data-bs-dismiss="modal">
                        <i class="icofont-refresh me-1"></i>Réessayer
                    </button>
                </div>

                <!-- Document Container -->
                <div id="iframe-container" class="bg-white">
                    <!-- Document will be inserted here dynamically -->
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <div class="modal-info small text-muted">
                        <i class="icofont-info-circle me-1"></i>
                        Utilisez les contrôles du document pour zoomer ou naviguer
                    </div>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
