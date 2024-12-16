"use strict";

let AppCreditIndexManager = (function () {
    const initSelects = () => {
        console.log("hello");
        jQuery("#user_id").select2({
            placeholder: "Choisir le Client",
            allowClear: true,
            dropdownParent: jQuery("#createemp"),
        });
    };

    return {
        init: () => {
            initSelects();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppCreditIndexManager.init();
});
