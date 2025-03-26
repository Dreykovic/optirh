"use strict";

let AppDocRequestListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#documentRequestsTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppDocRequestListManager.init();
});
