"use strict";

let AppHolidaysListManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#holidaysTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppHolidaysListManager.init();
});
