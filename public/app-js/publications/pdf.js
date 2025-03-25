"use strict";

/**
 * OptiHR Publication PDF Module
 * Handles PDF and document preview functionality
 */
const optiHRPublicationPDF = (function () {
    // Private variables
    let pdfModal;
    let downloadBtns;
    let activeDownloadBtn;
    let iframeContainer;
    let loadingIndicator;
    let errorContainer;

    /**
     * Handles document download and preview
     */
    const handleDownload = () => {
        if (!downloadBtns || !downloadBtns.length) return;

        downloadBtns.each((index, downloadBtn) => {
            $(downloadBtn).on("click", async (e) => {
                e.preventDefault();

                // Store active button for focus management
                activeDownloadBtn = downloadBtn;

                const publicationId =
                    $(downloadBtn).data("publication-id") || "";
                const fileName =
                    $(downloadBtn).find(".file-name").text().trim() ||
                    "document";
                const downloadUrl =
                    "/opti-hr/publications/pdf/preview/" + publicationId;

                // Show loading state
                showLoading(true);
                updateAccessibilityStatus("Chargement du document en cours...");

                try {
                    const response = await axios.get(downloadUrl, {
                        responseType: "blob",
                    });
                    const contentType = response.headers["content-type"];
                    const blob = new Blob([response.data], {
                        type: contentType,
                    });
                    const url = window.URL.createObjectURL(blob);

                    // Clear previous content
                    iframeContainer.empty();

                    // Set appropriate content based on file type
                    if (contentType === "application/pdf") {
                        const iframe = $(
                            `<iframe src="${url}" title="${fileName}" width="100%" height="600px" class="border-0"></iframe>`
                        );
                        iframeContainer.append(iframe);

                        // Set up accessibility for iframe
                        iframe.on("load", function () {
                            updateAccessibilityStatus(
                                `Document PDF "${fileName}" chargé avec succès`
                            );
                        });
                    } else if (contentType.startsWith("image/")) {
                        const img = $(
                            `<img src="${url}" alt="${fileName}" class="img-fluid mx-auto d-block preview-image" />`
                        );
                        iframeContainer.append(img);
                        updateAccessibilityStatus(
                            `Image "${fileName}" chargée avec succès`
                        );
                    } else {
                        throw new Error("Format non supporté");
                    }

                    // Show modal
                    pdfModal.modal("show");

                    // Update modal title with filename
                    pdfModal.find(".modal-title").text(fileName);

                    // Add download button
                    const downloadLink =
                        $(`<a href="${url}" download="${fileName}" class="btn btn-sm btn-primary me-2">
                        <i class="icofont-download me-1"></i>Télécharger
                    </a>`);

                    pdfModal.find(".modal-download-actions").html(downloadLink);

                    // Hide loading indicator
                    showLoading(false);
                } catch (error) {
                    // Handle different error scenarios
                    showLoading(false);
                    showError(getErrorMessage(error));

                    console.error("Erreur lors du chargement :", error);
                }
            });
        });

        // Handle modal close - cleanup resources
        pdfModal.on("hidden.bs.modal", function () {
            // Clear iframe to prevent memory leaks
            iframeContainer.empty();

            // Reset modal state
            pdfModal.find(".modal-title").text("Aperçu du document");
            pdfModal.find(".modal-download-actions").empty();

            // Return focus to the element that opened the modal
            if (activeDownloadBtn) {
                $(activeDownloadBtn).focus();
                activeDownloadBtn = null;
            }

            // Clear error messages
            hideError();
        });
    };

    /**
     * Shows or hides the loading indicator
     * @param {boolean} isLoading - Whether to show the loading indicator
     */
    const showLoading = (isLoading) => {
        if (loadingIndicator) {
            if (isLoading) {
                loadingIndicator.removeClass("d-none");
                iframeContainer.addClass("d-none");
                hideError();
            } else {
                loadingIndicator.addClass("d-none");
                iframeContainer.removeClass("d-none");
            }
        }
    };

    /**
     * Displays an error message
     * @param {string} message - Error message to display
     */
    const showError = (message) => {
        if (errorContainer) {
            errorContainer
                .removeClass("d-none")
                .find(".error-message")
                .text(message);

            iframeContainer.addClass("d-none");
            updateAccessibilityStatus(`Erreur: ${message}`);
        }
    };

    /**
     * Hides the error message
     */
    const hideError = () => {
        if (errorContainer) {
            errorContainer.addClass("d-none");
        }
    };

    /**
     * Gets a user-friendly error message based on the error
     * @param {Error} error - The error object
     * @returns {string} User-friendly error message
     */
    const getErrorMessage = (error) => {
        if (error.response) {
            switch (error.response.status) {
                case 404:
                    return "Le fichier demandé n'existe pas ou a été supprimé.";
                case 413:
                    return "Le fichier est trop volumineux (limite: 10 Mo).";
                case 401:
                    return "Vous n'êtes pas autorisé à accéder à ce document.";
                default:
                    return `Une erreur est survenue (${error.response.status}).`;
            }
        }
        return "Impossible de charger le document. Veuillez réessayer.";
    };

    /**
     * Updates the accessibility status for screen readers
     * @param {string} message - Status message to announce
     */
    const updateAccessibilityStatus = (message) => {
        const statusElement = document.getElementById("a11yAnnouncer");
        if (statusElement) {
            statusElement.textContent = message;
        }
    };

    /**
     * Handles file input preview in the publication form
     */
    const setupFilePreview = () => {
        const fileElement = document.getElementById("file");
        if (!fileElement) return;

        fileElement.addEventListener("change", function () {
            const files = this.files;
            const fileListDiv = document.getElementById("fileList");

            // Clear file list
            if (fileListDiv) {
                fileListDiv.innerHTML = "";

                if (files.length > 0) {
                    fileListDiv.style.display = "flex";

                    Array.from(files).forEach((file) => {
                        const fileType = file.type;
                        const fileItem = document.createElement("div");
                        fileItem.classList.add(
                            "file-item",
                            "border",
                            "rounded",
                            "p-2",
                            "d-flex",
                            "align-items-center",
                            "bg-light",
                            "me-2",
                            "mb-2"
                        );

                        // Determine icon based on file type
                        let iconClass = "icofont-file-alt text-warning";
                        if (fileType === "application/pdf") {
                            iconClass = "icofont-file-pdf text-danger";
                        } else if (fileType.startsWith("image/")) {
                            iconClass = "icofont-image text-success";
                        }

                        fileItem.innerHTML = `
                            <i class="${iconClass} fs-5 me-2"></i>
                            <span class="file-name text-truncate max-width-150">${
                                file.name
                            }</span>
                            <span class="file-size text-muted ms-2">${formatFileSize(
                                file.size
                            )}</span>
                            <button type="button" class="btn-close ms-2" aria-label="Supprimer ${
                                file.name
                            }"></button>
                        `;

                        // Add remove functionality
                        const removeBtn = fileItem.querySelector(".btn-close");
                        if (removeBtn) {
                            removeBtn.addEventListener("click", () => {
                                fileItem.remove();

                                // Check if all files are removed
                                if (fileListDiv.children.length === 0) {
                                    fileListDiv.style.display = "none";
                                }
                            });
                        }

                        fileListDiv.appendChild(fileItem);
                    });

                    // Update accessibility status
                    updateAccessibilityStatus(
                        `${files.length} fichier${
                            files.length > 1 ? "s" : ""
                        } sélectionné${files.length > 1 ? "s" : ""}`
                    );
                } else {
                    fileListDiv.style.display = "none";
                    updateAccessibilityStatus("Aucun fichier sélectionné");
                }
            }
        });
    };

    /**
     * Formats file size in a human-readable format
     * @param {number} bytes - Size in bytes
     * @returns {string} Formatted size
     */
    const formatFileSize = (bytes) => {
        if (bytes === 0) return "0 Octets";

        const k = 1024;
        const sizes = ["Octets", "Ko", "Mo", "Go"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + " " + sizes[i];
    };

    /**
     * Ensures chat history scrolls to bottom
     */
    const scrollToBottom = () => {
        const chatHistory = document.querySelector(".chat-container");
        if (chatHistory) {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }
    };

    /**
     * Formats date and time displays
     */
    const formatDateTimes = () => {
        document.querySelectorAll(".message-time").forEach((element) => {
            const dateString =
                element.getAttribute("title") || element.textContent.trim();
            const date = new Date(dateString);
            const now = new Date();

            // Check if it's today
            const isToday =
                date.getDate() === now.getDate() &&
                date.getMonth() === now.getMonth() &&
                date.getFullYear() === now.getFullYear();

            // Check if it's yesterday
            const yesterday = new Date(now);
            yesterday.setDate(now.getDate() - 1);
            const isYesterday =
                date.getDate() === yesterday.getDate() &&
                date.getMonth() === yesterday.getMonth() &&
                date.getFullYear() === yesterday.getFullYear();

            // Format time
            const hours = date.getHours().toString().padStart(2, "0");
            const minutes = date.getMinutes().toString().padStart(2, "0");

            let formattedDate;
            if (isToday) {
                formattedDate = `Aujourd'hui, ${hours}h${minutes}`;
            } else if (isYesterday) {
                formattedDate = `Hier, ${hours}h${minutes}`;
            } else {
                const options = {
                    weekday: "long",
                    day: "2-digit",
                    month: "short",
                };
                formattedDate = `${date.toLocaleDateString(
                    "fr-FR",
                    options
                )}, ${hours}h${minutes}`;
            }

            element.textContent = formattedDate;
        });
    };

    /**
     * Enables keyboard navigation for the UI
     */
    const setupKeyboardNavigation = () => {
        // Add keyboard support for publication items
        document
            .querySelectorAll(".publication-actions .dropdown-toggle")
            .forEach((btn) => {
                btn.addEventListener("keydown", (e) => {
                    // Open dropdown on Enter or Space
                    if (e.key === "Enter" || e.key === " ") {
                        e.preventDefault();
                        $(btn).dropdown("toggle");
                    }
                });
            });

        // Handle Escape key for modal
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && pdfModal) {
                $(pdfModal).modal("hide");
            }
        });
    };

    // Public methods
    return {
        init: () => {
            // Initialize components
            pdfModal = $("#cont-pdf-view");
            if (!pdfModal.length) return;

            // Get reference to UI elements
            downloadBtns = $(".downloadBtn");
            iframeContainer = $("#iframe-container");
            loadingIndicator = $("#pdf-loading");
            errorContainer = $("#pdf-error");

            // Set up features
            setupFilePreview();
            handleDownload();
            scrollToBottom();
            formatDateTimes();
            setupKeyboardNavigation();

            // Add event listeners for modal accessibility
            pdfModal.on("shown.bs.modal", function () {
                // Focus on close button when modal opens
                const closeButton = pdfModal.find(".btn-close").first();
                if (closeButton.length) {
                    closeButton.focus();
                }
            });
        },
    };
})();

// Initialize on DOM content loaded
document.addEventListener("DOMContentLoaded", () => {
    optiHRPublicationPDF.init();
});
