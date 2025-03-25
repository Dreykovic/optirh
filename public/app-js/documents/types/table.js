"use strict";

let AppDocTypeListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#documentTypesTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppDocTypeListManager.init();
});
