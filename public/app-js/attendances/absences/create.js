"use strict";

/**
 * Gestionnaire de création de demande d'absence
 * Gère le formulaire de demande d'absence et ses validations
 */
const AppAbsenceRequestCreateManager = (function () {
    // Variables pour les éléments DOM
    let startDateInput;
    let endDateInput;
    let daysRequestedElement;
    let absenceTypeSelect;
    let absenceReasonTextarea;
    let addressInput;
    let form;
    let deductibleStatus;
    let deductibleText;
    let absenceBalanceInfo;
    let currentBalanceSpan;
    
    // Variables d'état
    let currentAbsenceBalance = 30; // Valeur par défaut
    let isDeductible = false;
    let requestedDays = 0;
    
    /**
     * Calcule le nombre de jours entre deux dates
     */
    function calculateWorkingDays(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (start > end) return 0;
        
        const diffTime = Math.abs(end - start);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        return diffDays;
    }
    
    /**
     * Met à jour l'affichage du nombre de jours demandés
     */
    function updateDaysRequested() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (!startDate || !endDate) {
            daysRequestedElement.textContent = "0 jour";
            requestedDays = 0;
            return;
        }
        
        // Vérifier les dates
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const startDateObj = new Date(startDate);
        const endDateObj = new Date(endDate);
        
        if (startDateObj < today) {
            daysRequestedElement.textContent = "Date invalide";
            daysRequestedElement.style.color = "#dc3545";
            requestedDays = 0;
            return;
        }
        
        if (endDateObj < startDateObj) {
            daysRequestedElement.textContent = "Dates invalides";
            daysRequestedElement.style.color = "#dc3545";
            requestedDays = 0;
            return;
        }
        
        // Calculer les jours
        const workingDays = calculateWorkingDays(startDate, endDate);
        requestedDays = workingDays;
        
        daysRequestedElement.textContent = `${workingDays} jour${workingDays > 1 ? "s" : ""}`;
        daysRequestedElement.style.color = "";
        
        // Mettre à jour les infos de solde
        updateDeductibleInfo();
    }
    
    /**
     * Met à jour les informations de déductibilité
     */
    function updateDeductibleInfo() {
        if (!absenceTypeSelect || !absenceTypeSelect.options) return;
        
        const selectedOption = absenceTypeSelect.options[absenceTypeSelect.selectedIndex];
        
        if (!selectedOption || selectedOption.value === "") {
            if (deductibleStatus) deductibleStatus.style.display = "none";
            if (absenceBalanceInfo) absenceBalanceInfo.style.display = "none";
            isDeductible = false;
            return;
        }
        
        // Déterminer si l'absence est déductible
        isDeductible = selectedOption.dataset && selectedOption.dataset.deductible === "true";
        
        // Mettre à jour l'affichage si les éléments existent
        if (deductibleStatus) {
            deductibleStatus.style.display = "block";
            deductibleStatus.className = isDeductible ? 
                "alert alert-warning mt-2" : 
                "alert alert-info mt-2";
            
            if (deductibleText) {
                deductibleText.innerHTML = isDeductible ? 
                    "<i class='bi bi-calendar-minus me-2'></i> Cette absence sera déduite de votre solde de congés." : 
                    "<i class='bi bi-calendar-plus me-2'></i> Cette absence ne sera pas déduite de votre solde de congés.";
            }
        }
        
        // Afficher les infos de solde si déductible
        if (absenceBalanceInfo) {
            absenceBalanceInfo.style.display = isDeductible ? "block" : "none";
            
            if (isDeductible && currentBalanceSpan) {
                currentBalanceSpan.textContent = currentAbsenceBalance;
                
                // Avertir si le solde est insuffisant
                if (requestedDays > currentAbsenceBalance) {
                    const warningDiv = document.createElement("div");
                    warningDiv.className = "alert alert-danger mt-2";
                    warningDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> Attention: Vous demandez ${requestedDays} jours, mais votre solde est de ${currentAbsenceBalance} jours.`;
                    
                    // Supprimer l'ancien avertissement s'il existe
                    const oldWarning = absenceBalanceInfo.querySelector(".alert-danger");
                    if (oldWarning) {
                        absenceBalanceInfo.removeChild(oldWarning);
                    }
                    
                    absenceBalanceInfo.appendChild(warningDiv);
                } else {
                    // Supprimer l'avertissement s'il existe
                    const oldWarning = absenceBalanceInfo.querySelector(".alert-danger");
                    if (oldWarning) {
                        absenceBalanceInfo.removeChild(oldWarning);
                    }
                }
            }
        }
    }
    
    /**
     * Configure la validation des dates
     */
    function setupDateValidation() {
        // Définir la date minimale comme aujourd'hui
        const today = new Date().toISOString().split("T")[0];
        if (startDateInput) startDateInput.min = today;
        
        // Événements de changement
        if (startDateInput) {
            startDateInput.addEventListener("change", function() {
                if (this.value && endDateInput) {
                    endDateInput.min = this.value;
                    if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                        endDateInput.value = this.value;
                    }
                }
                updateDaysRequested();
            });
        }
        
        if (endDateInput) {
            endDateInput.addEventListener("change", updateDaysRequested);
        }
        
        if (absenceTypeSelect) {
            absenceTypeSelect.addEventListener("change", updateDeductibleInfo);
        }
    }
    
    return {
        /**
         * Initialise le gestionnaire
         */
        init: function() {
            // Récupérer les éléments DOM
            startDateInput = document.getElementById("absenceStartDate");
            endDateInput = document.getElementById("absenceEndDate");
            daysRequestedElement = document.querySelector(".days-requested");
            absenceTypeSelect = document.getElementById("absenceTypeSelect");
            absenceReasonTextarea = document.getElementById("absenceReason");
            addressInput = document.getElementById("absenceAddress");
            form = document.getElementById("modelAddForm");
            deductibleStatus = document.getElementById("deductibleStatus");
            deductibleText = document.getElementById("deductibleText");
            absenceBalanceInfo = document.getElementById("absenceBalanceInfo");
            currentBalanceSpan = document.getElementById("currentBalance");
            
            // Vérifier que les éléments existent
            if (!startDateInput || !endDateInput || !daysRequestedElement || !absenceTypeSelect) {
                console.error("Erreur: certains éléments du formulaire n'ont pas été trouvés");
                return;
            }
            
            // Configurer les validations
            setupDateValidation();
            
            // Configuration de Select2 si disponible
            if (typeof $ !== "undefined" && typeof $.fn.select2 !== "undefined") {
                $(absenceTypeSelect).select2({
                    placeholder: "Sélectionnez un type d'absence",
                    allowClear: true,
                    width: "100%"
                }).on("change", updateDeductibleInfo);
            }
            
            // Initialisation
            updateDaysRequested();
            updateDeductibleInfo();
        },
        
        /**
         * Valide le formulaire
         */
        validateForm: function() {
            // Réinitialiser les messages d'erreur
            document.querySelectorAll(".invalid-feedback").forEach(el => el.remove());
            document.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));
            
            let isValid = true;
            
            // Valider le type d'absence
            if (!absenceTypeSelect.value) {
                this.addErrorTo(absenceTypeSelect, "Veuillez sélectionner un type d'absence");
                isValid = false;
            }
            
            // Valider l'adresse
            if (!addressInput.value.trim()) {
                this.addErrorTo(addressInput, "L'adresse pendant l'absence est requise");
                isValid = false;
            }
            
            // Valider les dates
            if (!startDateInput.value) {
                this.addErrorTo(startDateInput, "La date de début est requise");
                isValid = false;
            } else {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (new Date(startDateInput.value) < today) {
                    this.addErrorTo(startDateInput, "La date de début doit être à partir d'aujourd'hui");
                    isValid = false;
                }
            }
            
            if (!endDateInput.value) {
                this.addErrorTo(endDateInput, "La date de fin est requise");
                isValid = false;
            }
            
            if (startDateInput.value && endDateInput.value && 
                new Date(startDateInput.value) > new Date(endDateInput.value)) {
                this.addErrorTo(endDateInput, "La date de fin doit être postérieure à la date de début");
                isValid = false;
            }
            
            return isValid;
        },
        
        /**
         * Ajoute une erreur à un élément
         */
        addErrorTo: function(element, message) {
            element.classList.add("is-invalid");
            const feedback = document.createElement("div");
            feedback.className = "invalid-feedback";
            feedback.textContent = message;
            element.parentNode.appendChild(feedback);
        },
        
        /**
         * Renvoie l'état actuel
         */
        getState: function() {
            return {
                isDeductible: isDeductible,
                requestedDays: requestedDays,
                currentBalance: currentAbsenceBalance,
                hasSufficientBalance: !isDeductible || requestedDays <= currentAbsenceBalance
            };
        }
    };
})();

/**
 * Gestionnaire de soumission du formulaire
 */
const AppModelCreateManager = (function() {
    let formElement;
    let submitButton;
    let confirmationModal;
    let confirmButton;
    
    /**
     * Configure la soumission du formulaire
     */
    function setupFormSubmission() {
        if (!formElement) return;
        
        formElement.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Valider le formulaire
            if (typeof AppAbsenceRequestCreateManager !== "undefined") {
                if (!AppAbsenceRequestCreateManager.validateForm()) {
                    return false;
                }
                
                // Vérifier si confirmation nécessaire
                const state = AppAbsenceRequestCreateManager.getState();
                if (state.isDeductible && !state.hasSufficientBalance && confirmationModal) {
                    showConfirmationModal(state);
                    return false;
                }
            }
            
            // Soumettre le formulaire
            submitForm();
        });
        
        // Configurer le bouton de confirmation
        if (confirmButton) {
            confirmButton.addEventListener("click", function() {
                submitForm();
                
                // Fermer la modal
                if (typeof bootstrap !== "undefined" && confirmationModal) {
                    const modal = bootstrap.Modal.getInstance(confirmationModal);
                    if (modal) modal.hide();
                }
            });
        }
    }
    
    /**
     * Affiche la modal de confirmation
     */
    function showConfirmationModal(state) {
        if (!confirmationModal) return;
        
        const messageDiv = confirmationModal.querySelector(".modal-body");
        if (messageDiv) {
            messageDiv.innerHTML = `
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Attention :</strong> Vous demandez ${state.requestedDays} jours d'absence, 
                    mais votre solde actuel est de ${state.currentBalance} jours seulement.
                </div>
                <p>Voulez-vous vraiment continuer avec cette demande ?</p>
            `;
        }
        
        // Afficher la modal
        if (typeof bootstrap !== "undefined") {
            const modal = new bootstrap.Modal(confirmationModal);
            modal.show();
        }
    }
    
    /**
     * Soumet le formulaire
     */
    function submitForm() {
        if (!formElement || !submitButton) return;
        
        // Afficher l'indicateur de chargement
        const normalStatus = submitButton.querySelector(".normal-status");
        const loadingIndicator = submitButton.querySelector(".indicateur");
        
        if (normalStatus) normalStatus.classList.add("d-none");
        if (loadingIndicator) loadingIndicator.classList.remove("d-none");
        submitButton.disabled = true;
        
        // Récupérer les données et l'URL
        const formData = new FormData(formElement);
        const submitUrl = formElement.getAttribute("data-model-add-url");
        
        // Envoyer la requête
        fetch(submitUrl, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || ""
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Erreur réseau");
            return response.json();
        })
        .then(data => {
            if (data.ok) {
                // Succès
                showNotification("success", data.message || "Demande soumise avec succès");
                
                // Redirection
                if (data.redirect) {
                    setTimeout(() => { window.location.href = data.redirect; }, 1000);
                } else {
                    setTimeout(() => { window.location.reload(); }, 1000);
                }
            } else {
                // Erreur
                showNotification("error", data.message || "Une erreur est survenue");
                resetSubmitButton();
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
            showNotification("error", "Une erreur est survenue lors de la soumission");
            resetSubmitButton();
        });
    }
    
    /**
     * Réinitialise le bouton de soumission
     */
    function resetSubmitButton() {
        if (!submitButton) return;
        
        const normalStatus = submitButton.querySelector(".normal-status");
        const loadingIndicator = submitButton.querySelector(".indicateur");
        
        if (normalStatus) normalStatus.classList.remove("d-none");
        if (loadingIndicator) loadingIndicator.classList.add("d-none");
        submitButton.disabled = false;
    }
    
    /**
     * Affiche une notification
     */
    function showNotification(type, message) {
        if (typeof toastr !== "undefined") {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
    
    return {
        /**
         * Initialise le gestionnaire
         */
        init: function() {
            formElement = document.getElementById("modelAddForm");
            submitButton = document.getElementById("modelAddBtn");
            confirmationModal = document.getElementById("confirmationModal");
            confirmButton = document.getElementById("confirmSubmit");
            
            if (!formElement || !submitButton) {
                console.error("Éléments du formulaire non trouvés");
                return;
            }
            
            setupFormSubmission();
        }
    };
})();

/**
 * Initialisation au chargement du document
 */
document.addEventListener("DOMContentLoaded", function() {
    // Initialiser les gestionnaires
    AppAbsenceRequestCreateManager.init();
    AppModelCreateManager.init();
    
    // Initialiser les tooltips Bootstrap si disponibles
    if (typeof bootstrap !== "undefined" && typeof bootstrap.Tooltip !== "undefined") {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltipTriggerList.length > 0) {
            [].slice.call(tooltipTriggerList).map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }
});