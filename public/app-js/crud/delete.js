"use strict";

let AppAdminModelDeleteManager = (function () {
    let deleteButtons;

    const handleModelDelete = () => {
        deleteButtons.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();
                const thisElement = e.target.closest(
                    '[data-model-action="delete"]'
                );
                const deleteUrl = thisElement.getAttribute(
                    "data-model-delete-url"
                );
                const parentSelector = thisElement.getAttribute(
                    "data-model-parent-selector"
                );
                console.log(parentSelector);

                const parent = thisElement.closest(parentSelector);
                console.log(parent);

                const value = parent.querySelector(".model-value").innerText;
                AppModules.deleteTableItemSubmission(
                    btn,
                    parent,
                    value,
                    deleteUrl
                );
            });
        });
    };
    return {
        init: () => {
            deleteButtons = document.querySelectorAll(".modelDeleteBtn");

            handleModelDelete();
            // console.log(445);
        },
    };
})();
document.addEventListener("DOMContentLoaded", (e) => {
    AppAdminModelDeleteManager.init();
});
