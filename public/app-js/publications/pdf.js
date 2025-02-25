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
            let files = this.files;
            let fileListDiv = document.getElementById("fileList");

            // Vider la liste avant d'afficher les nouveaux fichiers
            fileListDiv.innerHTML = "";

            if (files.length > 0) {
                Array.from(files).forEach((file) => {
                    let fileType = file.type;
                    let fileItem = document.createElement("div");
                    fileItem.classList.add(
                        "d-flex",
                        "align-items-center",
                        "mb-2"
                    );

                    // Déterminer l'icône en fonction du type de fichier
                    let icon = document.createElement("i");
                    icon.classList.add("fs-5", "me-2");

                    if (fileType === "application/pdf") {
                        icon.classList.add("icofont-file-pdf", "text-danger"); // Icône PDF rouge
                    } else if (fileType.startsWith("image/")) {
                        icon.classList.add("icofont-image", "text-success"); // Icône image verte
                    } else {
                        icon.classList.add("icofont-file-alt", "text-warning"); // Icône générique
                    }

                    // Créer l'élément texte avec le nom du fichier
                    let fileName = document.createElement("span");
                    fileName.textContent = file.name;
                    fileName.classList.add("text-muted");

                    // Ajouter l'icône et le nom du fichier au conteneur
                    fileItem.appendChild(icon);
                    fileItem.appendChild(fileName);
                    fileListDiv.appendChild(fileItem);
                });
            } else {
                fileListDiv.innerHTML =
                    "<span class='text-muted'>Aucun fichier sélectionné</span>";
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
