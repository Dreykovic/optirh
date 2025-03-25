"use strict";
let optiHRPublicationPDF = (function () {
    let pdfModal;

    let downloadBtns;

    const handleDownload = () => {
        downloadBtns.each((index, downloadBtn) => {
            $(downloadBtn).on("click", (e) => {
                e.preventDefault();
                const publicationId =
                    $(downloadBtn).data("publication-id") ?? "";
                console.log(publicationId);
                const downloadUrl =
                    "/opti-hr/publications/pdf/preview/" + publicationId;
                console.log(downloadUrl);

                axios
                    .get(downloadUrl, { responseType: "blob" })
                    .then((response) => {
                        const contentType = response.headers["content-type"];
                        const blob = new Blob([response.data], {
                            type: contentType,
                        });
                        const url = window.URL.createObjectURL(blob);

                        try {
                            console.log(url);
                            let iframeContainer = $("#iframe-container");
                            let iframe;

                            if (contentType === "application/pdf") {
                                iframe = `<iframe src="${url}" width="100%" height="600px"></iframe>`;
                            } else if (contentType.startsWith("image/")) {
                                iframe = `<img src="${url}" class="pdf-image" width="100%" />`;
                            } else {
                                throw new Error("Format non supporté");
                            }

                            iframeContainer.html(iframe);
                            pdfModal.modal("show");
                        } catch (e) {
                            console.warn(
                                "Le navigateur ne supporte pas l'affichage, redirection..."
                            );
                            window.location.href = downloadUrl;
                        }
                    })
                    .catch((error) => {
                        if (error.response && error.response.status === 404) {
                            console.error("Le fichier n'existe pas.");
                            AppModules.showConfirmAlert(
                                "Le fichier n'existe pas.",
                                "error"
                            );
                        } else if (
                            error.response &&
                            error.response.status === 413
                        ) {
                            console.error("Le fichier n'a pa bien chargé.");
                            AppModules.showConfirmAlert(
                                "Le fichier n'a pas bien chargé. IL DÉPASSE LA LIMITE DE 10mb",
                                "error"
                            );
                        } else {
                            console.error(
                                "Erreur lors du téléchargement :",
                                error
                            );
                            AppModules.showConfirmAlert(
                                "Une erreur s'est produite. Veuillez réessayer." +
                                    JSON.stringify(error),
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
        const fileElement = document.getElementById("file");
        if (fileElement) {
            fileElement.addEventListener("change", function () {
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
                            icon.classList.add(
                                "icofont-file-pdf",
                                "text-danger"
                            ); // Icône PDF rouge
                        } else if (fileType.startsWith("image/")) {
                            icon.classList.add("icofont-image", "text-success"); // Icône image verte
                        } else {
                            icon.classList.add(
                                "icofont-file-alt",
                                "text-warning"
                            ); // Icône générique
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
        }
    };
    const scrollBottom = () => {
        const chatHistory = document.querySelector(".chat-history");
        if (chatHistory) {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }
    };
    const formateTime = () => {
        function formatDateTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();

            // Vérifie si c'est aujourd'hui
            const isToday =
                date.getDate() === now.getDate() &&
                date.getMonth() === now.getMonth() &&
                date.getFullYear() === now.getFullYear();

            // Options pour formater la date
            const options = {
                weekday: "long",
                day: "2-digit",
                month: "short",
            };

            // Formater l'heure en 24h
            const hours = date.getHours().toString().padStart(2, "0");
            const minutes = date.getMinutes().toString().padStart(2, "0");
            const seconds = date.getSeconds().toString().padStart(2, "0");

            if (isToday) {
                return `Aujourd'hui, ${hours}h${minutes}:${seconds}`;
            } else {
                return `${date.toLocaleDateString(
                    "fr-FR",
                    options
                )}, ${hours}h${minutes}:${seconds}`;
            }
        }

        // Appliquer le formatage sur tous les éléments de classe .message-time
        document.querySelectorAll(".message-time").forEach((element) => {
            const originalDate = element.textContent.trim();
            element.textContent = formatDateTime(originalDate);
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
            scrollBottom();
            formateTime();
        },
    };
})();
document.addEventListener("DOMContentLoaded", (e) => {
    optiHRPublicationPDF.init();
});
