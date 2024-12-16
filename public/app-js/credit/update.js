"use strict";
let AppModelUpdateManager = (function () {
    let updateModelForms;

    let modelAddBtns;

    const handleModelAdd = () => {
        modelAddBtns.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();

                const tr = btn.closest("tr");
                const updateModelUrl = tr.getAttribute("data-model-update-url");

                // Trouver les champs d'entrée spécifiques
                const balanceInput = tr.querySelector('input[name="balance"]');
                const interestRateInput = tr.querySelector(
                    'input[name="interest_rate"]'
                );
                const agreementDateInput = tr.querySelector(
                    'input[name="agreement_date"]'
                );
                const dueDateInput = tr.querySelector('input[name="due_date"]');

                // Récupérer les valeurs des champs
                const balanceValue = balanceInput ? balanceInput.value : "";
                const interestRateValue = interestRateInput
                    ? interestRateInput.value
                    : "";
                const agreementDateValue = agreementDateInput
                    ? agreementDateInput.value
                    : "";
                const dueDateValue = dueDateInput ? dueDateInput.value : "";

                // Envoyer la requête au serveur

                console.log(agreementDateInput.value);

                submitForms(updateModelUrl, {
                    balance: balanceValue,
                    interest_rate: interestRateValue,
                    agreement_date: agreementDateValue,
                    due_date: dueDateValue,
                });
            });
        });
    };

    let submitForms = (url, data) => {
        axios
            .put(url, data)
            .then(function (response) {
                if (response.data.success) {
                    AppModules.showConfirmAlert(
                        "Credit modifié avec succès",
                        "success"
                    ).then(function (result) {
                        if (result.isDismissed || result.isConfirmed) {
                            if (callback == null || callback == undefined) {
                                return response;
                            } else {
                                callback();
                            }
                        }
                    });
                }
            })
            .catch(function (error) {
                AppModules.showConfirmAlert(
                    "Erreur de soumission du formulaire:" +
                        error.response.data.message
                );
            });
    };

    let addModelCallback = () => {
        location.reload();
    };
    return {
        init: () => {
            updateModelForms = document.querySelectorAll(".update-credit-form");

            if (!updateModelForms) {
                return;
            }

            modelAddBtns = document.querySelectorAll(".updateCreditBtn");

            handleModelAdd();
        },
    };
})();
document.addEventListener("DOMContentLoaded", (e) => {
    AppModelUpdateManager.init();
});
