"use strict";

let AppAbsenceRequestCreateManager = (function () {
    let startDateInput = document.getElementById("absenceStartDate");
    let endDateInput = document.getElementById("absenceEndDate");
    let daysRequestedElement = document.querySelector(".days-requested");

    // Fonction pour calculer le nombre de jours ouvrés entre deux dates
    function calculateWorkingDays(startDate, endDate) {
        let count = 0;
        let currentDate = new Date(startDate);
        endDate = new Date(endDate);

        while (currentDate <= endDate) {
            const dayOfWeek = currentDate.getDay();

            // Exclure les samedis (6) et dimanches (0)
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
            daysRequestedElement.textContent = `${workingDays} jour${
                workingDays > 1 ? "s" : ""
            }`;
        } else {
            daysRequestedElement.textContent = "";
        }
    }

    return {
        init: () => {
            startDateInput = document.getElementById("absenceStartDate");
            endDateInput = document.getElementById("absenceEndDate");
            daysRequestedElement = document.querySelector(".days-requested");
            if (!startDateInput || !endDateInput || !daysRequestedElement) {
                console.log("error de get element");

                return;
            }
            // Ajouter des écouteurs d'événements sur les champs de saisie de date
            startDateInput.addEventListener("change", updateDaysRequested);
            endDateInput.addEventListener("change", updateDaysRequested);
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppAbsenceRequestCreateManager.init();
});
