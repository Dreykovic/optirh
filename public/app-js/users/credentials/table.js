"use strict";

let AppUserCredentialsManager = (function () {
    return {
        init: () => {
            AppModules.initDataTable("#usersTable");
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppUserCredentialsManager.init();
});
