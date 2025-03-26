"use strict";
let AppAdminModelUpdateManager = (function () {
    let modelUpdateFormContainers;

    const handleModelUpdate = () => {
        modelUpdateFormContainers.forEach((modelUpdateFormContainer) => {
            const updateModelFormId = modelUpdateFormContainer.id;
            console.log("Form id: ", updateModelFormId);

            const modelUpdateBtn = document.querySelector(
                `#${updateModelFormId} .modelUpdateBtn`
            );
            const modelUpdateForm = document.querySelector(
                `#${updateModelFormId} form`
            );
            const updateModelUrl = modelUpdateForm.getAttribute(
                "data-model-update-url"
            );
            // console.log(modelUpdateBtn);
            // console.log(modelUpdateForm);
            // console.log(updateModelUrl);

            modelUpdateBtn.addEventListener("click", (e) => {
                e.preventDefault();
                const formData = new FormData(modelUpdateForm);
                console.log(formData);

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
            modelUpdateFormContainers = document.querySelectorAll(
                ".modelUpdateFormContainer"
            );
            console.log("Updates Containers: ", modelUpdateFormContainers);

            if (!modelUpdateFormContainers) {
                return;
            }

            handleModelUpdate();
        },
    };
})();
document.addEventListener("DOMContentLoaded", (e) => {
    AppAdminModelUpdateManager.init();
});
