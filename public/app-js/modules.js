"use strict";

// Class definition
const AppModules = (function () {
    // Public functions
    return {
        // Initialization
        formatMontant: (text) => {
            text = text.trim();
            text = text.split("").reverse().join("");
            var length = text.length;
            var newText = "";
            for (var i = 0; i <= length - 1; i++) {
                if ((i + 1) % 3 === 1 && i != 1) {
                    newText += " ";
                }
                newText += text[i];
            }
            newText = newText.split("").reverse().join("");

            return newText;
        },
        showSpinner: (btn) => {
            if (!btn) return;

            let spinner = btn.querySelector(".indicateur");
            let normalStatut = btn.querySelector(".normal-status");

            spinner.classList.remove("d-none");
            normalStatut.style.display = "none";
            btn.disabled = true;
        },

        hideSpinner: (btn) => {
            if (!btn) return;

            let spinner = btn.querySelector(".indicateur");
            let normalStatut = btn.querySelector(".normal-status");

            spinner.classList.add("d-none");
            normalStatut.style.display = "block";
            btn.disabled = false;
        },
        showAskAlert: (message = "") => {
            return Swal.fire({
                html: message,
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Oui, Supprimer",
                cancelButtonText: "Non, Annuler",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
            });
        },
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
                    confirmButton: "btn btn-primary",
                },
            });
        },

        submitFromBtn: (btn, formData, url, callback) => {
            if (btn == null || !formData || !url) {
                return "something is not get";
            }

            // Validate form

            AppModules.showSpinner(btn);

            console.log(formData);console.log(url);
            

            // Check axios library docs: https://axios-http.com/docs/intro
            axios
                .post(url, formData)
                .then(function (response) {
                    console.log(url);
                    if (response.data.ok) {
                        // Hide loading indication
                        AppModules.hideSpinner(btn);
                        AppModules.showConfirmAlert(
                            response.data.message,
                            "success"
                        ).then(function (result) {
                            if (result.isDismissed || result.isConfirmed) {
                                if (callback == null || callback == undefined) {
                                    return response;
                                } else {
                                    callback();
                                }
                            }
                        });
                        console.log(1);
                    } else {
                        console.log(0);
                        console.log('response',response);

                        console.log(response.data);

                        AppModules.hideSpinner(btn);
                        AppModules.showConfirmAlert(response.data.message).then(
                            function (result) {
                                if (result.isDismissed || result.isConfirmed) {
                                    if (
                                        callback == null ||
                                        callback == undefined
                                    ) {
                                        return response;
                                    } else {
                                        callback();
                                    }
                                }
                            }
                        );
                    }
                })
                .catch(function (error) {
                    if (
                        error.response &&
                        error.response.data &&
                        error.response.data.message
                    ) {
                        // Remove loading indication
                        console.error("Erreur de validation détectée.");
                        console.error(error.response.data); // Affiche la réponse complète pour debug
                        let errorMessages = error.response.data.message;
                        AppModules.hideSpinner(btn);

                        if (error.response.data.errors) {
                            // Crée une seule chaîne de message à partir de toutes les erreurs
                            errorMessages +=
                                ":<br> " +
                                Object.values(error.response.data.errors)
                                    .flatMap((messages) => messages) // Transforme chaque tableau de messages en une seule liste
                                    .join("\n"); // Combine tous les messages avec un retour à la ligne
                        }
                        console.error(errorMessages);
                        AppModules.showConfirmAlert(errorMessages).then(
                            function (result) {
                                if (result.isDismissed || result.isConfirmed) {
                                    if (
                                        callback == null ||
                                        callback == undefined
                                    ) {
                                        return response;
                                    } else {
                                        callback();
                                    }
                                }
                            }
                        );
                    } else {
                        //  Remove loading indication
                        AppModules.hideSpinner(btn);

                        AppModules.showConfirmAlert(
                            "Erreur de soumission du formulaire:" + error
                        ).then(function (result) {
                            if (result.isDismissed || result.isConfirmed) {
                                if (callback == null || callback == undefined) {
                                    return response;
                                } else {
                                    callback();
                                }
                            }
                        });
                    }
                });
        },
        deleteTableItemSubmission: (btn, parent, item, url) => {
            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            AppModules.showAskAlert(
                "Etes-vous sûr que vous voulez supprimer " + item + "?"
            ).then(function (result) {
                if (result.value) {
                    AppModules.showSpinner(btn);

                    //  console.log(15000)
                    axios
                        .delete(url)
                        .then((response) => {
                            if (response.data.ok) {
                                AppModules.hideSpinner(btn);

                                AppModules.showConfirmAlert(
                                    response.data.message,
                                    "success"
                                ).then(function (result) {
                                    if (
                                        result.isDismissed ||
                                        result.isConfirmed
                                    ) {
                                        // Remove current row
                                        if (parent) {
                                            parent.remove();
                                        }
                                    }
                                });
                            } else {
                                AppModules.showConfirmAlert(
                                    response.data.message
                                );
                                AppModules.hideSpinner(btn);
                            }
                        })
                        .catch((error) => {
                            if (
                                error.response &&
                                error.response.data &&
                                error.response.data.message
                            ) {
                                AppModules.hideSpinner(btn);

                                console.error(
                                    "Erreur de soumission du formulaire:",
                                    error.response.data.message
                                );
                                AppModules.showConfirmAlert(
                                    "Erreur de soumission du formulaire:",
                                    error.response.data.message
                                );
                            } else {
                                AppModules.showConfirmAlert(
                                    "Erreur de soumission du formulaire:" +
                                        error
                                );
                                AppModules.hideSpinner(btn);
                            }
                        });
                } else if (result.dismiss === "cancel") {
                    AppModules.showConfirmAlert(item + " N'a pas été effacé");
                    AppModules.hideSpinner(btn);
                }
            });
        },
        resetForms: () => {
            let forms = document.getElementsByTagName("form");
            for (const element of forms) {
                element.reset();
            }
        },
        initSelect2: (selector, placeholder) => {
            jQuery(selector).select2({
                placeholder,
                allowClear: true,
            });
        },
        initDataTable: (selector) => {
            // project data table

            $(selector)
                .addClass("nowrap")
                .dataTable({
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
                });
        },
        deleteRow: () => {
            $(".deleterow").on("click", function () {
                var tablename = $(this).closest("table").DataTable();
                tablename.row($(this).parents("tr")).remove().draw();
            });
        },
        init: function () {
            console.log("all modules loaded");
            this.resetForms();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppModules.init();
});
