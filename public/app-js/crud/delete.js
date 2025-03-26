"use strict";

let AppAdminModelDeleteManager = (function () {
    let deleteButtons;

    const handleModelDelete = () => {
        $(document).on("click", '[data-model-action="delete"]', function (e) {
            e.preventDefault();

            const $thisElement = $(this);
            const deleteUrl = $thisElement.data("model-delete-url");
            let parentSelector = $thisElement.data("model-parent-selector");

            // Ajuster le sélecteur si nécessaire
            if (parentSelector === "tr.parent") {
                parentSelector = 'tr[class*="parent"]';
            }

            const $parent = $thisElement.closest(parentSelector);
            const $modelValueElement = $parent.find(".model-value");
            const value = $modelValueElement.length
                ? $modelValueElement.text()
                : "actuel";

            AppModules.deleteTableItemSubmission(
                this,
                $parent[0],
                value,
                deleteUrl
            );
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
