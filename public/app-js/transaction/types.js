"use strict";

let AppTransactionTypesListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#transTypesTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppTransactionTypesListManager.init();
});
