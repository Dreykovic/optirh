"use strict";

let AppAbsenceTypeListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#absenceTypesTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppAbsenceTypeListManager.init();
});
