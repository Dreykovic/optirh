"use strict";
let AppAdminModelUpdateManager = (function () {
    let modelUpdateForms;

    const handleModelUpdate = () => {
        modelUpdateForms.forEach((modelUpdateForm) => {
            const updateModelUrl = modelUpdateForm.getAttribute(
                "data-model-update-url"
            );

            const modelUpdateBtn =
                modelUpdateForm.querySelector(".modelUpdateBtn");

            modelUpdateBtn.addEventListener("click", (e) => {
                e.preventDefault();
                const formData = new FormData(modelUpdateForm);
                AppModules.submitFromBtn(
                    modelUpdateBtn,
                    formData,
                    updateModelUrl,
                    modelUpdateCallback
                );
            });
        });
    };

    let modelUpdateCallback = () => {
        location.reload();
    };
    return {
        init: () => {
            modelUpdateForms = document.querySelectorAll(".modelUpdateForm");
            // console.log(modelUpdateForms);

            if (!modelUpdateForms) {
                return;
            }

            handleModelUpdate();
        },
    };
})();
document.addEventListener("DOMContentLoaded", (e) => {
    AppAdminModelUpdateManager.init();
});
