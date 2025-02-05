"use strict";

let AppAbsenceTypeListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#holidaysTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppAbsenceTypeListManager.init();
});
