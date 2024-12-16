"use strict";

let AppWithdrawActionManager = (function () {
    let withdrawalForm;
    let clientId;
    let accountTypeSelect;
    const getClientAccounts = () => {
        const clientSelect = withdrawalForm.querySelector("#client_id");
        // console.log(clientSelect);
        $("#client_id").on("select2:select", (event) => {
            // console.log("Test");

            clientId = event.params.data.element.value;
            // console.log(clientId);

            if (!clientId) {
                accountTypeSelect.disabled = true;

                return;
            }
            accountTypeSelect.disabled = false;
            viderSelect(accountTypeSelect);
            axios
                .get(`/actions/client-accounts/${clientId}`)
                .then((response) => {
                    // console.log(response.data);

                    let options = response.data.accounts;
                    console.log(options);

                    if (response.data.ok) {
                        options.forEach((account) => {
                            var option = document.createElement("option");
                            option.value = account.id;
                            option.text = account.type;
                            // console.log(option);

                            accountTypeSelect.add(option);
                        });
                    }
                });
        });
    };

    const initSelects = () => {
        accountTypeSelect.disabled = true;
        viderSelect(accountTypeSelect);
        AppModules.initSelect2("#client_id", "Choisir un Client");
    };
    // Fonction pour vider le select
    function viderSelect(select) {
        select.innerHTML = ""; // Supprime toutes les options du <select>
    }

    return {
        init: () => {
            withdrawalForm = document.querySelector(".withdrawalForm");

            // Vérification que les formulaires existent
            if (!withdrawalForm) {
                return;
            }
            accountTypeSelect = withdrawalForm.querySelector("#account_id");
            AppModules.initDataTable("#operationTable");
            initSelects();
            getClientAccounts();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppWithdrawActionManager.init();
});
