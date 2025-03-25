"use strict";

// Gestionnaire de création de demande d'absence
let AppAbsenceRequestCreateManager = (function () {
    let startDateInput;
    let endDateInput;
    let daysRequestedElement;
    let absenceTypeSelect;
    let absenceReasonTextarea;
    let form;

    // Fonction pour calculer le nombre de jours ouvrés entre deux dates
    function calculateWorkingDays(startDate, endDate) {
        let count = 0;
        let currentDate = new Date(startDate);
        endDate = new Date(endDate);

        // Vérifier que la date de début est antérieure à la date de fin
        if (currentDate > endDate) {
            return 0;
        }

        while (currentDate <= endDate) {
            const dayOfWeek = currentDate.getDay();

            // Exclure uniquement les samedis (6) et dimanches (0)
            // Les jours fériés sont inclus dans le calcul
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                count++;
            }

            // Passer au jour suivant
            currentDate.setDate(currentDate.getDate() + 1);
        }

        return count;
    }

    // Fonction de mise à jour de l'affichage du nombre de jours demandés
    function updateDaysRequested() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            const workingDays = calculateWorkingDays(startDate, endDate);

            if (workingDays === 0 && new Date(startDate) > new Date(endDate)) {
                daysRequestedElement.textContent = "Date invalide";
                daysRequestedElement.style.color = "red";
            } else {
                daysRequestedElement.textContent = `${workingDays} jour${
                    workingDays > 1 ? "s" : ""
                }`;
                daysRequestedElement.style.color = "";
            }
        } else {
            daysRequestedElement.textContent = "0 jour";
        }
    }

    // Ajouter la validation des dates
    function setupDateValidation() {
        // Définir la date minimale comme aujourd'hui
        const today = new Date();
        const formattedDate = today.toISOString().split("T")[0];
        startDateInput.setAttribute("min", formattedDate);

        // Mettre à jour la date minimale de fin quand la date de début change
        startDateInput.addEventListener("change", function () {
            if (this.value) {
                endDateInput.setAttribute("min", this.value);
                // Si la date de fin est déjà définie et est antérieure à la nouvelle date de début
                if (
                    endDateInput.value &&
                    new Date(endDateInput.value) < new Date(this.value)
                ) {
                    endDateInput.value = this.value;
                }
            }
            updateDaysRequested();
        });

        endDateInput.addEventListener("change", updateDaysRequested);
    }

    // Configurer Select2 pour améliorer les listes déroulantes
    function setupSelect2() {
        $(absenceTypeSelect).select2({
            placeholder: "Sélectionnez un type d'absence",
            allowClear: true,
            width: "100%",
        });
    }

    // Ajouter un message d'erreur après un champ
    function appendErrorMessage(element, message) {
        element.classList.add("is-invalid");
        const errorDiv = document.createElement("div");
        errorDiv.className = "invalid-feedback";
        errorDiv.textContent = message;
        element.parentNode.appendChild(errorDiv);
    }

    return {
        init: () => {
            // Initialiser les éléments DOM
            startDateInput = document.getElementById("absenceStartDate");
            endDateInput = document.getElementById("absenceEndDate");
            daysRequestedElement = document.querySelector(".days-requested");
            absenceTypeSelect = document.getElementById("absenceTypeSelect");
            absenceReasonTextarea = document.getElementById("absenceReason");
            form = document.getElementById("modelAddForm");

            if (
                !startDateInput ||
                !endDateInput ||
                !daysRequestedElement ||
                !absenceTypeSelect ||
                !form
            ) {
                console.error(
                    "Erreur: certains éléments du formulaire n'ont pas été trouvés."
                );
                return;
            }

            // Configurer la validation des dates
            setupDateValidation();

            // Configurer Select2 si jQuery est disponible
            if (
                typeof $ !== "undefined" &&
                typeof $.fn.select2 !== "undefined"
            ) {
                setupSelect2();
            }

            // Initialiser l'affichage des jours demandés
            updateDaysRequested();
        },
    };
})();

// Gestionnaire de soumission du formulaire
let AppAbsenceCreateManager = (function () {
    let addModelForm;
    let modelAddBtn;

    const handleModelAdd = () => {
        const addModelUrl = addModelForm.getAttribute("data-model-add-url");

        addModelForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            // Validation du formulaire avant soumission
            if (
                addModelForm.id === "modelAddForm" &&
                typeof AppAbsenceRequestCreateManager !== "undefined"
            ) {
                // Validation personnalisée pour le formulaire d'absence
                if (!validateAbsenceForm(e)) {
                    return false;
                }
            }

            const formData = new FormData(addModelForm);
            AppModules.submitFromBtn(
                modelAddBtn,
                formData,
                addModelUrl,
                addModelCallback
            );
        });
    };

    // Fonction de validation du formulaire d'absence
    const validateAbsenceForm = (e) => {
        const startDateInput = document.getElementById("absenceStartDate");
        const endDateInput = document.getElementById("absenceEndDate");
        const absenceTypeSelect = document.getElementById("absenceTypeSelect");

        let isValid = true;

        // Réinitialiser les messages d'erreur
        document
            .querySelectorAll(".invalid-feedback")
            .forEach((el) => el.remove());
        document
            .querySelectorAll(".is-invalid")
            .forEach((el) => el.classList.remove("is-invalid"));

        // Valider le type d'absence
        if (!absenceTypeSelect.value) {
            appendErrorMessage(
                absenceTypeSelect,
                "Veuillez sélectionner un type d'absence"
            );
            isValid = false;
        }

        // Valider les dates
        if (!startDateInput.value) {
            appendErrorMessage(startDateInput, "La date de début est requise");
            isValid = false;
        }

        if (!endDateInput.value) {
            appendErrorMessage(endDateInput, "La date de fin est requise");
            isValid = false;
        }

        if (
            startDateInput.value &&
            endDateInput.value &&
            new Date(startDateInput.value) > new Date(endDateInput.value)
        ) {
            appendErrorMessage(
                endDateInput,
                "La date de fin doit être postérieure à la date de début"
            );
            isValid = false;
        }

        return isValid;
    };

    // Fonction pour ajouter un message d'erreur
    const appendErrorMessage = (element, message) => {
        element.classList.add("is-invalid");
        const errorDiv = document.createElement("div");
        errorDiv.className = "invalid-feedback";
        errorDiv.textContent = message;
        element.parentNode.appendChild(errorDiv);
    };

    let addModelCallback = (response) => {
        console.log(response.data.redirect);
        if (response.data.redirect) {
            location.href = response.data.redirect;
        } else {
            location.reload();
        }
    };

    return {
        init: () => {
            addModelForm = document.querySelector("#modelAddForm");

            if (!addModelForm) {
                return;
            }

            modelAddBtn = addModelForm.querySelector("#modelAddBtn");

            handleModelAdd();
        },
    };
})();

// Initialisation des gestionnaires au chargement du document
document.addEventListener("DOMContentLoaded", (e) => {
    // Initialiser d'abord le gestionnaire de demande d'absence
    AppAbsenceRequestCreateManager.init();

    // Puis initialiser le gestionnaire de soumission du formulaire
    AppAbsenceCreateManager.init();
});
