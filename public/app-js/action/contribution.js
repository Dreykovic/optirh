"use strict";

let AppContributionActionManager = (function () {
    const initSelects = () => {
        console.log("hello");

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
    AppContributionActionManager.init();
});
