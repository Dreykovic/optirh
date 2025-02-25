"use strict";
let optiHRPublicationPDF = (function () {
    let pdfModal;

    let downloadBtns;

    const handleDownload = () => {
        downloadBtns.each((index, downloadBtn) => {
            // console.log(downloadBtn);
            $(downloadBtn).on("click", (e) => {
                e.preventDefault();
                const publicationId =
                    $(downloadBtn).data("publication-id") ?? "";
                console.log(publicationId);
                const downloadUrl =
                    "/publications/pdf/preview/" + publicationId;
                console.log(downloadUrl);
                axios
                    .get(downloadUrl, {
                        responseType: "blob", // indique que la réponse est un blob
                    })
                    .then((response) => {
                        if (
                            window.navigator &&
                            window.navigator.msSaveOrOpenBlob
                        ) {
                            // Pour Internet Explorer et Edge
                            window.navigator.msSaveOrOpenBlob(
                                response.data,
                                "publication.pdf"
                            );
                        } else {
                            const blob = new Blob([response.data], {
                                type: "application/pdf",
                            });
                            const url = window.URL.createObjectURL(blob);

                            try {
                                console.log(url);

                                let iframeContainer = $("#iframe-container");
                                let iframe = `<iframe src="${url}" width="100%" height="600px">
                        </iframe> `;
                                iframeContainer.html(iframe);
                                pdfModal.modal("show");
                            } catch (e) {
                                console.warn(
                                    "Le navigateur ne supporte pas les Blobs, redirection..."
                                );
                                window.location.href = downloadUrl; // Redirection vers l'URL si l'affichage ne marche pas
                            }
                        }
                    })

                    .catch((error) => {
                        if (error.response && error.response.status === 404) {
                            console.error("Le PDF n'existe pas.");
                            AppModules.showConfirmAlert(
                                "Le PDF n'existe pas.",
                                "error"
                            );
                        } else {
                            console.error(
                                "Erreur lors du téléchargement du PDF:",
                                error.message
                            );
                            AppModules.showConfirmAlert(
                                "Une erreur s'est produite. Veuillez réessayer.",
                                "error"
                            );
                        }
                    });
            });
        });
    };

    let addNatureCallback = () => {
        location.reload();
    };
    const showPdf = () => {
        document.getElementById("file").addEventListener("change", function () {
            let file = this.files[0];
            let fileNameSpan = document.getElementById("fileName");

            if (file) {
                let allowedTypes = [
                    "image/jpeg",
                    "image/png",
                    "image/gif",
                    "application/pdf",
                ];
                if (allowedTypes.includes(file.type)) {
                    fileNameSpan.textContent = file.name;
                    fileNameSpan.classList.remove("text-danger");
                    fileNameSpan.classList.add("text-success"); // Ajoute une couleur verte pour valider le fichier
                } else {
                    fileNameSpan.textContent = "Format non autorisé !";
                    fileNameSpan.classList.add("text-danger"); // Ajoute une couleur rouge pour signaler une erreur
                    this.value = ""; // Réinitialise l'input
                }
            } else {
                fileNameSpan.textContent = "Aucun fichier sélectionné";
                fileNameSpan.classList.remove("text-success", "text-danger");
            }
        });
    };
    return {
        init: () => {
            pdfModal = $("#cont-pdf-view");
            if (!pdfModal) {
                return;
            }

            downloadBtns = $(".downloadBtn");
            showPdf();
            handleDownload();
        },
    };
})();
document.addEventListener("DOMContentLoaded", (e) => {
    optiHRPublicationPDF.init();
});
