"use strict";

let AppDepositActionManager = (function () {
    let withdrawalForm;

    const initSelects = () => {
        AppModules.initSelect2("#client_id", "Choisir le Client");
    };

    return {
        init: () => {
            AppModules.initDataTable("#operationTable");
            initSelects();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppDepositActionManager.init();
});
