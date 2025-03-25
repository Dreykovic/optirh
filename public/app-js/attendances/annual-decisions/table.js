"use strict";

let AppAbsenceTypeListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#decisionsTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppAbsenceTypeListManager.init();
});
