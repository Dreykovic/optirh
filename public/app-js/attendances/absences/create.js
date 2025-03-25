"use strict";

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

    // Valider le formulaire avant soumission
    function validateForm(e) {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        const absenceType = absenceTypeSelect.value;

        let isValid = true;

        // Réinitialiser les messages d'erreur
        document
            .querySelectorAll(".invalid-feedback")
            .forEach((el) => el.remove());
        document
            .querySelectorAll(".is-invalid")
            .forEach((el) => el.classList.remove("is-invalid"));

        // Valider le type d'absence
        if (!absenceType) {
            appendErrorMessage(
                absenceTypeSelect,
                "Veuillez sélectionner un type d'absence"
            );
            isValid = false;
        }

        // Valider les dates
        if (!startDate) {
            appendErrorMessage(startDateInput, "La date de début est requise");
            isValid = false;
        }

        if (!endDate) {
            appendErrorMessage(endDateInput, "La date de fin est requise");
            isValid = false;
        }

        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            appendErrorMessage(
                endDateInput,
                "La date de fin doit être postérieure à la date de début"
            );
            isValid = false;
        }

        // Si le formulaire n'est pas valide, empêcher sa soumission
        if (!isValid) {
            e.preventDefault();
            return false;
        }

        return true;
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

            // Ajouter la validation du formulaire avant la soumission
            form.addEventListener("submit", validateForm);

            // Initialiser l'affichage des jours demandés
            updateDaysRequested();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppAbsenceRequestCreateManager.init();
});
