"use strict";

/**
 * Gestionnaire des actions sur les demandes de documents
 * Ajoute des dialogues de confirmation avant les actions critiques
 */
let AppDocRequestActionsManager = (function () {
    /**
     * Affiche un dialogue de confirmation SweetAlert2
     * @param {Object} options - Options de configuration
     * @returns {Promise}
     */
    const showConfirmDialog = (options) => {
        return Swal.fire({
            title: options.title,
            text: options.text || "",
            icon: options.icon || "question",
            showCancelButton: true,
            confirmButtonText: options.confirmText || "Confirmer",
            cancelButtonText: "Annuler",
            confirmButtonColor: options.confirmColor || "#3085d6",
            cancelButtonColor: "#6c757d",
            reverseButtons: true,
        });
    };

    /**
     * Soumet le formulaire via le système existant
     * @param {HTMLElement} btn - Le bouton cliqué
     * @param {HTMLFormElement} form - Le formulaire
     */
    const submitForm = (btn, form) => {
        const url = form.getAttribute("data-model-update-url");
        if (!url) return;

        const formData = new FormData(form);
        formData.append("_method", "PUT");

        AppModules.submitFromBtn(btn, formData, url, () => {
            location.reload();
        });
    };

    /**
     * Initialise les écouteurs d'événements pour les boutons d'action
     */
    const initActionListeners = () => {
        // Intercepter les clics sur les boutons avec data-action
        document.addEventListener(
            "click",
            function (e) {
                const actionBtn = e.target.closest("[data-action]");
                if (!actionBtn) return;

                const action = actionBtn.getAttribute("data-action");
                const form = actionBtn.closest("form");
                if (!form) return;

                // Vérifier si c'est un formulaire de demande de document
                const formContainer = actionBtn.closest(
                    ".modelUpdateFormContainer"
                );
                if (
                    !formContainer ||
                    !formContainer.id.includes("documentRequest")
                )
                    return;

                e.preventDefault();
                e.stopPropagation();

                let dialogOptions = {};

                switch (action) {
                    case "approve":
                        dialogOptions = {
                            title: "Confirmer l'approbation ?",
                            text: "Cette demande sera approuvée et passera à l'étape suivante.",
                            icon: "question",
                            confirmText: "Approuver",
                            confirmColor: "#28a745",
                        };
                        break;

                    case "reject":
                        dialogOptions = {
                            title: "Confirmer le rejet ?",
                            text: "Cette demande sera définitivement rejetée.",
                            icon: "warning",
                            confirmText: "Rejeter",
                            confirmColor: "#dc3545",
                        };
                        break;

                    case "cancel":
                        dialogOptions = {
                            title: "Annuler cette demande ?",
                            text: "Vous ne pourrez plus la modifier après annulation.",
                            icon: "warning",
                            confirmText: "Oui, annuler",
                            confirmColor: "#6c757d",
                        };
                        break;

                    default:
                        return;
                }

                showConfirmDialog(dialogOptions).then((result) => {
                    if (result.isConfirmed) {
                        submitForm(actionBtn, form);
                    }
                });
            },
            true
        ); // Utiliser la phase de capture pour intercepter avant put.js
    };

    return {
        init: () => {
            initActionListeners();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppDocRequestActionsManager.init();
});
