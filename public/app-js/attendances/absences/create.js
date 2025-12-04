"use strict";

/**
 * Gestionnaire de création de demande d'absence - Version améliorée
 * Gère le formulaire de demande d'absence avec validation dynamique et UI moderne
 */
const AppAbsenceRequestCreateManager = (function () {
    // Éléments DOM
    const elements = {
        form: null,
        absenceTypeSelect: null,
        startDateInput: null,
        endDateInput: null,
        addressInput: null,
        reasonTextarea: null,
        proofInput: null,
        submitButton: null,
        // Éléments d'affichage
        deductibilityInfo: null,
        deductibilityText: null,
        balanceCard: null,
        balanceDisplay: null,
        balanceProgressBar: null,
        balanceAfterRequest: null,
        durationBadge: null,
        durationText: null,
        charCount: null,
        uploadZone: null,
        filePreviewContainer: null,
        summaryCard: null,
        // Données
        formData: null
    };

    // État
    const state = {
        absenceBalance: 30,
        maxBalance: 30,
        holidays: [],
        isDeductible: false,
        requestedDays: 0,
        selectedFile: null,
        isValid: false
    };

    /**
     * Initialise tous les éléments DOM
     */
    function initElements() {
        // Formulaire et boutons
        elements.form = document.getElementById("modelAddForm");
        elements.submitButton = document.getElementById("modelAddBtn");

        // Inputs
        elements.absenceTypeSelect = document.getElementById("absenceTypeSelect");
        elements.startDateInput = document.getElementById("absenceStartDate");
        elements.endDateInput = document.getElementById("absenceEndDate");
        elements.addressInput = document.getElementById("absenceAddress");
        elements.reasonTextarea = document.getElementById("absenceReason");
        elements.proofInput = document.getElementById("proofInput");

        // Éléments d'affichage
        elements.deductibilityInfo = document.getElementById("deductibilityInfo");
        elements.deductibilityText = document.getElementById("deductibilityText");
        elements.balanceCard = document.getElementById("balanceCard");
        elements.balanceDisplay = document.getElementById("balanceDisplay");
        elements.balanceProgressBar = document.getElementById("balanceProgressBar");
        elements.balanceAfterRequest = document.getElementById("balanceAfterRequest");
        elements.durationBadge = document.getElementById("durationBadge");
        elements.durationText = document.getElementById("durationText");
        elements.charCount = document.getElementById("charCount");
        elements.uploadZone = document.getElementById("uploadZone");
        elements.filePreviewContainer = document.getElementById("filePreviewContainer");
        elements.summaryCard = document.getElementById("summaryCard");

        // Données du formulaire
        elements.formData = document.getElementById("absenceFormData");

        // Charger les données initiales
        if (elements.formData) {
            state.absenceBalance = parseInt(elements.formData.dataset.absenceBalance) || 30;
            state.maxBalance = parseInt(elements.formData.dataset.maxBalance) || 30;
            try {
                state.holidays = JSON.parse(elements.formData.dataset.holidays || "[]");
            } catch (e) {
                state.holidays = [];
            }
        }
    }

    /**
     * Configure tous les événements
     */
    function setupEventListeners() {
        // Type d'absence
        if (elements.absenceTypeSelect) {
            elements.absenceTypeSelect.addEventListener("change", handleAbsenceTypeChange);

            // Select2 si disponible
            if (typeof $ !== "undefined" && typeof $.fn.select2 !== "undefined") {
                $(elements.absenceTypeSelect).select2({
                    placeholder: "Sélectionnez un type d'absence",
                    allowClear: true,
                    width: "100%"
                }).on("change", handleAbsenceTypeChange);
            }
        }

        // Dates
        if (elements.startDateInput) {
            elements.startDateInput.addEventListener("change", handleStartDateChange);
        }
        if (elements.endDateInput) {
            elements.endDateInput.addEventListener("change", handleDateChange);
        }

        // Adresse
        if (elements.addressInput) {
            elements.addressInput.addEventListener("input", debounce(validateForm, 300));
        }

        // Motif avec compteur de caractères
        if (elements.reasonTextarea) {
            elements.reasonTextarea.addEventListener("input", handleReasonInput);
        }

        // Upload de fichier
        setupFileUpload();

        // Soumission du formulaire
        if (elements.form) {
            elements.form.addEventListener("submit", handleFormSubmit);
        }
    }

    /**
     * Gère le changement de type d'absence
     */
    function handleAbsenceTypeChange() {
        const selectedOption = elements.absenceTypeSelect.options[elements.absenceTypeSelect.selectedIndex];

        if (!selectedOption || selectedOption.value === "") {
            hideElement(elements.deductibilityInfo);
            hideElement(elements.balanceCard);
            state.isDeductible = false;
            validateForm();
            return;
        }

        // Déterminer la déductibilité
        state.isDeductible = selectedOption.dataset.deductible === "true";

        // Afficher l'info de déductibilité
        showElement(elements.deductibilityInfo);
        if (elements.deductibilityInfo) {
            elements.deductibilityInfo.className = state.isDeductible
                ? "deductibility-info deductible"
                : "deductibility-info non-deductible";
        }
        if (elements.deductibilityText) {
            elements.deductibilityText.textContent = state.isDeductible
                ? "Cette absence sera déduite de votre solde de congés."
                : "Cette absence ne sera pas déduite de votre solde.";
        }

        // Afficher la carte de solde si déductible
        if (state.isDeductible) {
            showElement(elements.balanceCard);
            updateBalanceDisplay();
        } else {
            hideElement(elements.balanceCard);
        }

        validateForm();
        updateSummary();
    }

    /**
     * Gère le changement de la date de début
     */
    function handleStartDateChange() {
        const startValue = elements.startDateInput.value;

        if (startValue && elements.endDateInput) {
            elements.endDateInput.min = startValue;

            // Si la date de fin est avant la date de début, la corriger
            if (elements.endDateInput.value && elements.endDateInput.value < startValue) {
                elements.endDateInput.value = startValue;
            }
        }

        handleDateChange();
    }

    /**
     * Gère le changement des dates
     */
    function handleDateChange() {
        const startDate = elements.startDateInput?.value;
        const endDate = elements.endDateInput?.value;

        if (!startDate || !endDate) {
            hideElement(elements.durationBadge);
            state.requestedDays = 0;
            validateForm();
            return;
        }

        // Valider les dates
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const start = new Date(startDate);
        const end = new Date(endDate);

        if (start < today || end < start) {
            hideElement(elements.durationBadge);
            state.requestedDays = 0;
            validateForm();
            return;
        }

        // Calculer les jours ouvrés
        state.requestedDays = calculateWorkingDays(startDate, endDate);

        // Afficher la durée
        showElement(elements.durationBadge);
        if (elements.durationText) {
            elements.durationText.textContent = `${state.requestedDays} jour${state.requestedDays > 1 ? "s" : ""} ouvré${state.requestedDays > 1 ? "s" : ""}`;
        }

        // Mettre à jour le style du badge selon le solde
        if (elements.durationBadge) {
            elements.durationBadge.classList.remove("warning", "danger");
            if (state.isDeductible) {
                if (state.requestedDays > state.absenceBalance) {
                    elements.durationBadge.classList.add("danger");
                } else if (state.requestedDays > state.absenceBalance * 0.5) {
                    elements.durationBadge.classList.add("warning");
                }
            }
        }

        // Mettre à jour l'affichage du solde
        updateBalanceDisplay();
        validateForm();
        updateSummary();
    }

    /**
     * Calcule le nombre de jours ouvrés entre deux dates
     */
    function calculateWorkingDays(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        let count = 0;

        const current = new Date(start);
        while (current <= end) {
            const dayOfWeek = current.getDay();
            // Exclure samedi (6) et dimanche (0)
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                // Vérifier si ce n'est pas un jour férié
                const dateStr = current.toISOString().split("T")[0];
                if (!state.holidays.includes(dateStr)) {
                    count++;
                }
            }
            current.setDate(current.getDate() + 1);
        }

        return count;
    }

    /**
     * Met à jour l'affichage du solde
     */
    function updateBalanceDisplay() {
        if (!state.isDeductible || !elements.balanceCard) return;

        const balanceAfter = state.absenceBalance - state.requestedDays;
        const percentage = Math.max(0, (balanceAfter / state.maxBalance) * 100);

        // Mettre à jour les valeurs
        if (elements.balanceDisplay) {
            elements.balanceDisplay.textContent = state.absenceBalance;
        }

        if (elements.balanceProgressBar) {
            elements.balanceProgressBar.style.width = `${percentage}%`;
        }

        if (elements.balanceAfterRequest) {
            if (state.requestedDays > 0) {
                elements.balanceAfterRequest.textContent = `Après demande: ${balanceAfter} jours`;
            } else {
                elements.balanceAfterRequest.textContent = "";
            }
        }

        // Mettre à jour le style de la carte
        elements.balanceCard.classList.remove("warning", "danger");
        if (balanceAfter < 0) {
            elements.balanceCard.classList.add("danger");
        } else if (percentage < 30) {
            elements.balanceCard.classList.add("warning");
        }
    }

    /**
     * Gère l'input du motif
     */
    function handleReasonInput() {
        const length = elements.reasonTextarea.value.length;
        if (elements.charCount) {
            elements.charCount.textContent = length;
        }
        updateSummary();
    }

    /**
     * Configure l'upload de fichier avec drag & drop
     */
    function setupFileUpload() {
        if (!elements.uploadZone || !elements.proofInput) return;

        // Drag & drop
        elements.uploadZone.addEventListener("dragover", (e) => {
            e.preventDefault();
            elements.uploadZone.classList.add("drag-over");
        });

        elements.uploadZone.addEventListener("dragleave", () => {
            elements.uploadZone.classList.remove("drag-over");
        });

        elements.uploadZone.addEventListener("drop", (e) => {
            e.preventDefault();
            elements.uploadZone.classList.remove("drag-over");

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });

        // Sélection classique
        elements.proofInput.addEventListener("change", () => {
            if (elements.proofInput.files.length > 0) {
                handleFileSelect(elements.proofInput.files[0]);
            }
        });
    }

    /**
     * Gère la sélection d'un fichier
     */
    function handleFileSelect(file) {
        // Valider le fichier
        const validTypes = ["application/pdf", "image/jpeg", "image/jpg", "image/png"];
        const maxSize = 5 * 1024 * 1024; // 5 Mo

        if (!validTypes.includes(file.type)) {
            showNotification("error", "Type de fichier non autorisé. Utilisez PDF, JPG ou PNG.");
            return;
        }

        if (file.size > maxSize) {
            showNotification("error", "Le fichier est trop volumineux. Maximum 5 Mo.");
            return;
        }

        state.selectedFile = file;
        displayFilePreview(file);
    }

    /**
     * Affiche la prévisualisation du fichier
     */
    function displayFilePreview(file) {
        if (!elements.filePreviewContainer) return;

        // Déterminer l'icône
        let iconClass = "bi-file-earmark";
        let iconColorClass = "doc";
        if (file.type === "application/pdf") {
            iconClass = "bi-file-earmark-pdf";
            iconColorClass = "pdf";
        } else if (file.type.startsWith("image/")) {
            iconClass = "bi-file-earmark-image";
            iconColorClass = "image";
        }

        // Formater la taille
        const size = formatFileSize(file.size);

        elements.filePreviewContainer.innerHTML = `
            <div class="file-preview-item">
                <div class="file-preview-icon ${iconColorClass}">
                    <i class="bi ${iconClass}"></i>
                </div>
                <div class="file-preview-info">
                    <div class="file-preview-name">${escapeHtml(file.name)}</div>
                    <div class="file-preview-size">${size}</div>
                </div>
                <button type="button" class="file-preview-remove" id="removeFileBtn">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        `;

        // Bouton de suppression
        document.getElementById("removeFileBtn")?.addEventListener("click", () => {
            state.selectedFile = null;
            elements.filePreviewContainer.innerHTML = "";
            elements.proofInput.value = "";
        });
    }

    /**
     * Valide le formulaire
     */
    function validateForm() {
        let isValid = true;

        // Réinitialiser les états
        clearValidationStates();

        // Valider le type d'absence
        if (!elements.absenceTypeSelect?.value) {
            setInvalid(elements.absenceTypeSelect);
            isValid = false;
        } else {
            setValid(elements.absenceTypeSelect);
        }

        // Valider les dates
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (!elements.startDateInput?.value) {
            setInvalid(elements.startDateInput);
            isValid = false;
        } else if (new Date(elements.startDateInput.value) < today) {
            setInvalid(elements.startDateInput, "La date doit être à partir d'aujourd'hui");
            isValid = false;
        } else {
            setValid(elements.startDateInput);
        }

        if (!elements.endDateInput?.value) {
            setInvalid(elements.endDateInput);
            isValid = false;
        } else if (elements.startDateInput?.value && elements.endDateInput.value < elements.startDateInput.value) {
            setInvalid(elements.endDateInput, "La date de fin doit être postérieure à la date de début");
            isValid = false;
        } else {
            setValid(elements.endDateInput);
        }

        // Valider l'adresse
        if (!elements.addressInput?.value.trim()) {
            setInvalid(elements.addressInput);
            isValid = false;
        } else {
            setValid(elements.addressInput);
        }

        // Mettre à jour l'état
        state.isValid = isValid;

        // Activer/désactiver le bouton
        if (elements.submitButton) {
            elements.submitButton.disabled = !isValid;
        }

        // Afficher/masquer le résumé
        if (isValid) {
            showElement(elements.summaryCard);
        } else {
            hideElement(elements.summaryCard);
        }

        return isValid;
    }

    /**
     * Met à jour le résumé
     */
    function updateSummary() {
        if (!elements.summaryCard || !state.isValid) return;

        // Type d'absence
        const typeElement = document.getElementById("summaryType");
        if (typeElement && elements.absenceTypeSelect) {
            const selectedOption = elements.absenceTypeSelect.options[elements.absenceTypeSelect.selectedIndex];
            typeElement.textContent = selectedOption?.text || "-";
        }

        // Période
        const periodElement = document.getElementById("summaryPeriod");
        if (periodElement && elements.startDateInput?.value && elements.endDateInput?.value) {
            const startFormatted = formatDate(elements.startDateInput.value);
            const endFormatted = formatDate(elements.endDateInput.value);
            periodElement.textContent = `${startFormatted} → ${endFormatted}`;
        }

        // Durée
        const durationElement = document.getElementById("summaryDuration");
        if (durationElement) {
            durationElement.textContent = `${state.requestedDays} jour${state.requestedDays > 1 ? "s" : ""} ouvré${state.requestedDays > 1 ? "s" : ""}`;
        }

        // Solde
        const balanceElement = document.getElementById("summaryBalance");
        const balanceRow = document.getElementById("summaryBalanceRow");
        if (balanceElement && balanceRow) {
            if (state.isDeductible) {
                balanceRow.style.display = "";
                const balanceAfter = state.absenceBalance - state.requestedDays;
                balanceElement.textContent = `${balanceAfter} jours`;
                balanceElement.style.color = balanceAfter < 0 ? "var(--abs-danger)" : "";
            } else {
                balanceRow.style.display = "none";
            }
        }

        // Adresse
        const addressElement = document.getElementById("summaryAddress");
        if (addressElement && elements.addressInput) {
            addressElement.textContent = elements.addressInput.value || "-";
        }
    }

    /**
     * Gère la soumission du formulaire
     */
    function handleFormSubmit(e) {
        e.preventDefault();

        if (!validateForm()) {
            showNotification("error", "Veuillez remplir tous les champs obligatoires.");
            return;
        }

        // Avertissement si solde insuffisant
        if (state.isDeductible && state.requestedDays > state.absenceBalance) {
            if (!confirm(`Attention: Vous demandez ${state.requestedDays} jours mais votre solde est de ${state.absenceBalance} jours.\n\nVoulez-vous continuer?`)) {
                return;
            }
        }

        submitForm();
    }

    /**
     * Soumet le formulaire
     */
    function submitForm() {
        if (!elements.form || !elements.submitButton) return;

        const formData = new FormData(elements.form);
        const submitUrl = elements.form.getAttribute("data-model-add-url");

        if (!submitUrl) {
            console.error("URL de soumission non définie");
            return;
        }

        // Utiliser AppModules si disponible
        if (typeof AppModules !== "undefined" && AppModules.submitFromBtn) {
            AppModules.submitFromBtn(
                elements.submitButton,
                formData,
                submitUrl,
                handleSubmitResponse
            );
        } else {
            // Fallback avec fetch
            showLoading();

            fetch(submitUrl, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(handleSubmitResponse)
            .catch(error => {
                console.error("Erreur:", error);
                showNotification("error", "Une erreur est survenue lors de la soumission.");
                hideLoading();
            });
        }
    }

    /**
     * Gère la réponse de soumission
     */
    function handleSubmitResponse(response) {
        const data = response.data || response;

        if (data.ok) {
            showNotification("success", data.message || "Demande soumise avec succès!");

            if (data.redirect) {
                setTimeout(() => { window.location.href = data.redirect; }, 3000);
            } else {
                setTimeout(() => { window.location.reload(); }, 3000);
            }
        } else {
            showNotification("error", data.message || "Une erreur est survenue.");
            hideLoading();
        }
    }

    // === Utilitaires ===

    function showElement(el) {
        if (el) el.classList.remove("d-none");
    }

    function hideElement(el) {
        if (el) el.classList.add("d-none");
    }

    function setValid(el) {
        if (el) {
            el.classList.remove("is-invalid");
            el.classList.add("is-valid");
        }
    }

    function setInvalid(el, message) {
        if (el) {
            el.classList.remove("is-valid");
            el.classList.add("is-invalid");

            // Message personnalisé si fourni
            if (message) {
                const feedback = el.parentNode.querySelector(".invalid-feedback");
                if (feedback) feedback.textContent = message;
            }
        }
    }

    function clearValidationStates() {
        document.querySelectorAll(".is-valid, .is-invalid").forEach(el => {
            el.classList.remove("is-valid", "is-invalid");
        });
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const options = { day: "numeric", month: "short", year: "numeric" };
        return date.toLocaleDateString("fr-FR", options);
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + " o";
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + " Ko";
        return (bytes / (1024 * 1024)).toFixed(1) + " Mo";
    }

    function escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function showLoading() {
        if (!elements.submitButton) return;
        const normal = elements.submitButton.querySelector(".normal-status");
        const loading = elements.submitButton.querySelector(".indicateur");
        if (normal) normal.classList.add("d-none");
        if (loading) loading.classList.remove("d-none");
        elements.submitButton.disabled = true;
    }

    function hideLoading() {
        if (!elements.submitButton) return;
        const normal = elements.submitButton.querySelector(".normal-status");
        const loading = elements.submitButton.querySelector(".indicateur");
        if (normal) normal.classList.remove("d-none");
        if (loading) loading.classList.add("d-none");
        elements.submitButton.disabled = false;
    }

    function showNotification(type, message) {
        if (typeof toastr !== "undefined") {
            toastr[type](message);
        } else if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: type === "error" ? "error" : "success",
                title: type === "error" ? "Erreur" : "Succès",
                text: message,
                timer: 10000,
                showConfirmButton: false
            });
        } else {
            alert(message);
        }
    }

    // === API Publique ===
    return {
        init: function() {
            initElements();

            // Vérifier les éléments essentiels
            if (!elements.form || !elements.absenceTypeSelect) {
                console.error("Éléments du formulaire non trouvés");
                return;
            }

            setupEventListeners();

            // Initialisation
            validateForm();

            // Initialiser les tooltips Bootstrap
            if (typeof bootstrap !== "undefined" && bootstrap.Tooltip) {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }
        },

        getState: function() {
            return { ...state };
        },

        validate: validateForm
    };
})();

/**
 * Initialisation au chargement du DOM
 */
document.addEventListener("DOMContentLoaded", function() {
    AppAbsenceRequestCreateManager.init();
});
