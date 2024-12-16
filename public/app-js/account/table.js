"use strict";

let AppAccountListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#accountsTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppAccountListManager.init();
});
