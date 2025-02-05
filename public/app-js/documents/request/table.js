"use strict";

let AppAbsenceListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#absencesTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppAbsenceListManager.init();
});
