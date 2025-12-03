"use strict";

/**
 * AppModules - Bibliothèque d'utilitaires pour l'application
 * Version améliorée avec meilleure organisation, documentation et gestion des erreurs
 */
const AppModules = (function () {
    // Configuration globale
    const CONFIG = {
        ALERTS: {
            DELETE_CONFIRM: {
                TITLE: "Confirmation de suppression",
                CANCEL_TEXT: "Non, Annuler",
                CONFIRM_TEXT: "Oui, Supprimer",
                SUCCESS_MESSAGE: "L'élément a été supprimé avec succès.",
                CANCEL_MESSAGE: " n'a pas été effacé",
            },
            CLASSES: {
                CONFIRM_BUTTON: "btn fw-bold btn-primary",
                DELETE_BUTTON: "btn fw-bold btn-danger",
                CANCEL_BUTTON: "btn fw-bold btn-active-light-primary",
            },
        },
        DATATABLES: {
            DEFAULTS: {
                responsive: true,
                language: {
                    processing: "Traitement en cours...",
                    search: "Rechercher&nbsp;:",
                    lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
                    info: "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                    infoEmpty:
                        "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                    infoFiltered:
                        "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                    infoPostFix: "",
                    loadingRecords: "Chargement en cours...",
                    zeroRecords:
                        "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    emptyTable:
                        "Aucune donn&eacute;e disponible dans le tableau",
                    paginate: {
                        first: "Premier",
                        previous: "Pr&eacute;c&eacute;dent",
                        next: "Suivant",
                        last: "Dernier",
                    },
                    aria: {
                        sortAscending:
                            ": activer pour trier la colonne par ordre croissant",
                        sortDescending:
                            ": activer pour trier la colonne par ordre d&eacute;croissant",
                    },
                },
            },
        },
        SELECT2: {
            DEFAULT_OPTIONS: {
                allowClear: true,
            },
        },
    };

    // Méthodes privées
    const _private = {
        /**
         * Gère et formate les messages d'erreur provenant des réponses du serveur
         * @param {Object} error - L'objet d'erreur retourné par axios
         * @returns {String} Le message d'erreur formaté
         */
        formatErrorMessage: (error) => {
            let errorMessage = "Une erreur s'est produite";

            if (error.response && error.response.data) {
                const { data } = error.response;

                if (data.message) {
                    errorMessage = data.message;

                    // Ajout des erreurs détaillées si disponibles
                    if (data.errors) {
                        errorMessage += ":<br>";
                        errorMessage += Object.values(data.errors)
                            .flat()
                            .map((msg) => `• ${msg}`)
                            .join("<br>");
                    }
                }
            } else if (error.message) {
                errorMessage = error.message;
            }

            return errorMessage;
        },

        /**
         * Vérifie si un élément DOM existe
         * @param {HTMLElement} element - L'élément à vérifier
         * @returns {Boolean} True si l'élément existe, false sinon
         */
        elementExists: (element) => {
            return element !== null && element !== undefined;
        },

        /**
         * Effectue un log de débogage si le mode debug est activé
         * @param {String} type - Le type de log (log, error, warn, info)
         * @param {String} message - Le message principal
         * @param {Any} data - Les données supplémentaires à logger
         */
        debug: (type = "log", message, data = null) => {
            if (data) {
                console[type](message, data);
            } else {
                console[type](message);
            }
        },
    };

    // Méthodes publiques
    return {
        /**
         * Formate un montant avec des séparateurs d'espaces pour les milliers
         * @param {String} text - Le montant à formater
         * @returns {String} Le montant formaté
         */
        formatMontant: (text) => {
            if (!text || typeof text !== "string") return "";

            text = text.trim();
            const reversed = text.split("").reverse();
            let formatted = [];

            reversed.forEach((char, index) => {
                if (index > 0 && index % 3 === 0) {
                    formatted.push(" ");
                }
                formatted.push(char);
            });

            return formatted.reverse().join("");
        },

        /**
         * Affiche un indicateur de chargement sur un bouton
         * @param {HTMLElement} btn - L'élément bouton
         */
        showSpinner: (btn) => {
            if (!_private.elementExists(btn)) return;

            const spinner = btn.querySelector(".indicateur");
            const normalStatus = btn.querySelector(".normal-status");

            if (spinner && normalStatus) {
                spinner.classList.remove("d-none");
                normalStatus.style.display = "none";
                btn.disabled = true;
            }
        },

        /**
         * Cache l'indicateur de chargement d'un bouton
         * @param {HTMLElement} btn - L'élément bouton
         */
        hideSpinner: (btn) => {
            if (!_private.elementExists(btn)) return;

            const spinner = btn.querySelector(".indicateur");
            const normalStatus = btn.querySelector(".normal-status");

            if (spinner && normalStatus) {
                spinner.classList.add("d-none");
                normalStatus.style.display = "block";
                btn.disabled = false;
            }
        },

        /**
         * Affiche une alerte de confirmation avec SweetAlert2
         * @param {String} message - Le message à afficher
         * @returns {Promise} La promesse de SweetAlert2
         */
        showAskAlert: (message = "") => {
            const config = CONFIG.ALERTS;

            return Swal.fire({
                html: message,
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: config.DELETE_CONFIRM.CONFIRM_TEXT,
                cancelButtonText: config.DELETE_CONFIRM.CANCEL_TEXT,
                customClass: {
                    confirmButton: config.CLASSES.DELETE_BUTTON,
                    cancelButton: config.CLASSES.CANCEL_BUTTON,
                },
            });
        },

        /**
         * Affiche une alerte d'information ou d'erreur avec SweetAlert2
         * @param {String} message - Le message à afficher
         * @param {String} status - Le statut (success, error, warning, info)
         * @param {String} confirm - Le texte du bouton de confirmation
         * @returns {Promise} La promesse de SweetAlert2
         */
        showConfirmAlert: (
            message = "",
            status = "error",
            confirm = "Ok, compris!"
        ) => {
            return Swal.fire({
                html: message,
                icon: status,
                buttonsStyling: false,
                confirmButtonText: confirm,
                customClass: {
                    confirmButton: CONFIG.ALERTS.CLASSES.CONFIRM_BUTTON,
                },
            });
        },

        /**
         * Affiche une notification toast discrète (en haut à droite)
         * @param {String} message - Le message à afficher
         * @param {String} status - Le statut (success, error, warning, info)
         * @param {String} title - Le titre personnalisé (optionnel)
         * @returns {Promise} La promesse de SweetAlert2
         */
        showToast: (message = "", status = "success", title = null) => {
            const titles = {
                success: "Succès",
                error: "Erreur",
                warning: "Attention",
                info: "Info",
            };

            return Swal.fire({
                icon: status,
                title: title || titles[status] || "Notification",
                text: message,
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 10000,
                timerProgressBar: true,
            });
        },

        /**
         * Soumet un formulaire via AJAX
         * @param {HTMLElement} btn - Le bouton de soumission
         * @param {FormData|Object} formData - Les données du formulaire
         * @param {String} url - L'URL de soumission
         * @param {Function} callback - La fonction de rappel après soumission
         */
        submitFromBtn: (btn, formData, url, callback) => {
            if (!_private.elementExists(btn) || !formData || !url) {
                _private.debug(
                    "error",
                    "Paramètres invalides pour submitFromBtn",
                    { btn, formData, url }
                );
                return;
            }

            AppModules.showSpinner(btn);

            axios
                .post(url, formData)
                .then((response) => {
                    AppModules.hideSpinner(btn);
                    const { data } = response;
                    const status = data.ok ? "success" : "error";

                    // Utiliser toast au lieu de showConfirmAlert
                    AppModules.showToast(data.message, status);

                    // Callback après un délai pour laisser le temps de lire le toast
                    setTimeout(() => {
                        if (typeof callback === "function") {
                            callback(response);
                        }
                    }, 3000);
                })
                .catch((error) => {
                    console.error("error", error);
                    AppModules.hideSpinner(btn);

                    const errorMessage = _private.formatErrorMessage(error);

                    // Toast d'erreur
                    AppModules.showToast(errorMessage, "error");

                    setTimeout(() => {
                        if (typeof callback === "function") {
                            callback({ error });
                        }
                    }, 3000);
                });
        },

        /**
         * Gère la suppression d'un élément de table
         * @param {HTMLElement} btn - Le bouton de suppression
         * @param {HTMLElement} parent - L'élément parent à supprimer
         * @param {String} item - Le nom de l'élément à supprimer
         * @param {String} url - L'URL de suppression
         */
        deleteTableItemSubmission: (btn, parent, item, url) => {
            if (!url) {
                _private.debug("error", "URL manquante pour la suppression");
                return;
            }

            const confirmMessage = `Êtes-vous sûr que vous voulez supprimer ${item} ?`;

            AppModules.showAskAlert(confirmMessage).then((result) => {
                if (result.value) {
                    AppModules.showSpinner(btn);

                    axios
                        .delete(url)
                        .then((response) => {
                            AppModules.hideSpinner(btn);

                            const { data } = response;

                            if (data.ok) {
                                AppModules.showConfirmAlert(
                                    data.message,
                                    "success"
                                ).then((result) => {
                                    if (
                                        result.isDismissed ||
                                        (result.isConfirmed && parent)
                                    ) {
                                        parent.remove();
                                    }
                                });
                            } else {
                                AppModules.showConfirmAlert(data.message);
                            }
                        })
                        .catch((error) => {
                            AppModules.hideSpinner(btn);

                            const errorMessage =
                                _private.formatErrorMessage(error);
                            AppModules.showConfirmAlert(errorMessage);
                        });
                } else if (result.dismiss === "cancel") {
                    AppModules.showConfirmAlert(
                        `${item}${CONFIG.ALERTS.DELETE_CONFIRM.CANCEL_MESSAGE}`
                    );
                }
            });
        },

        /**
         * Réinitialise tous les formulaires de la page
         */
        resetForms: () => {
            document.querySelectorAll("form").forEach((form) => form.reset());
        },

        /**
         * Initialise un select2 sur un sélecteur
         * @param {String} selector - Le sélecteur CSS
         * @param {String} placeholder - Le texte d'aide
         * @param {Object} options - Options supplémentaires pour Select2
         */
        initSelect2: (selector, placeholder, options = {}) => {
            if (!selector) return;

            try {
                const defaultOptions = {
                    ...CONFIG.SELECT2.DEFAULT_OPTIONS,
                    placeholder,
                };

                jQuery(selector).select2({
                    ...defaultOptions,
                    ...options,
                });
            } catch (error) {
                _private.debug(
                    "error",
                    "Erreur lors de l'initialisation de Select2",
                    error
                );
            }
        },

        /**
         * Initialise une DataTable sur un sélecteur
         * @param {String} selector - Le sélecteur CSS
         * @param {Object} options - Options supplémentaires pour DataTable
         */
        initDataTable: (selector, options = {}) => {
            if (!selector) return;

            try {
                const element = $(selector);

                if (element.length) {
                    element.addClass("nowrap").dataTable({
                        ...CONFIG.DATATABLES.DEFAULTS,
                        ...options,
                    });
                } else {
                    _private.debug(
                        "warn",
                        `Élément non trouvé pour DataTable: ${selector}`
                    );
                }
            } catch (error) {
                _private.debug(
                    "error",
                    "Erreur lors de l'initialisation de DataTable",
                    error
                );
            }
        },

        /**
         * Configure les gestionnaires d'événements pour supprimer des lignes
         * @param {String} selector - Le sélecteur pour les boutons de suppression (optionnel)
         */
        setupDeleteRowHandlers: (selector = ".deleterow") => {
            $(document).on("click", selector, function () {
                const table = $(this).closest("table");

                if (table.length && $.fn.DataTable.isDataTable(table)) {
                    const dataTable = table.DataTable();
                    dataTable.row($(this).parents("tr")).remove().draw();
                }
            });
        },

        /**
         * Initialise le module
         */
        init: function () {
            _private.debug("info", "Initialisation d'AppModules");

            // Réinitialisation des formulaires
            this.resetForms();

            // Configuration des gestionnaires d'événements pour la suppression de lignes
            this.setupDeleteRowHandlers();

            _private.debug("info", "AppModules initialisé avec succès");
        },
    };
})();

// Initialisation au chargement du DOM
document.addEventListener("DOMContentLoaded", () => {
    AppModules.init();
});
