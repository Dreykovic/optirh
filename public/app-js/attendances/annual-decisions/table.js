"use strict";

let AppDecisionsListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#decisionsTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppDecisionsListManager.init();
});
