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
        if (response.data.redirect) {
            location.href = response.data.redirect;
        } else {
            location.reload();
        }
    };
    return {
        init: () => {
            addModelForm = document.querySelector("#modelAddForm");
            console.log("Add model Form: ", addModelForm);

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
