"use strict";
let AppModelCreateManager = (function () {
    let addModelForm;

    let modelAddBtn;

    const handleModelAdd = () => {
        const addModelUrl = addModelForm.getAttribute("data-model-add-url");

        addModelForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(addModelForm);
            AppModules.submitFromBtn(
                modelAddBtn,
                formData,
                addModelUrl,
                addModelCallback
            );
        });
    };

    let addModelCallback = (response) => {
        if (response.redirect) {
            location.href = response.redirect;
        } else {
            location.reload();
        }
    };
    return {
        init: () => {
            addModelForm = document.querySelector("#modelAddForm");

            if (!addModelForm) {
                return;
            }

            modelAddBtn = addModelForm.querySelector("#modelAddBtn");

            handleModelAdd();
        },
    };
})();
document.addEventListener("DOMContentLoaded", (e) => {
    AppModelCreateManager.init();
});
