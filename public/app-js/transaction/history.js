"use strict";

let AppTransactionHistoryManager = (function () {
    let transactionCreateForm;
    let accountSelect;
    let transactionTypeSelect;
    const getClientAccounts = () => {
        // console.log(clientSelect);
        $("#client_id").on("select2:select", (event) => {
            // console.log("Test");
            let clientId;

            clientId = event.params.data.element.value;
            // console.log(clientId);

            if (!clientId) {
                accountSelect.disabled = true;

                return;
            }
            accountSelect.disabled = false;
            viderSelect(accountSelect);
            axios
                .get(`/transactions/client-accounts/${clientId}`)
                .then((response) => {
                    console.log(response.data);

                    let options = response.data.accounts;
                    console.log(options);

                    if (response.data.ok) {
                        options.forEach((account) => {
                            let option = document.createElement("option");
                            option.value = account.id;
                            option.text = `${account.account_no} (${account.account_type.name})`;

                            accountSelect.add(option);
                        });
                    }
                    AppModules.initSelect2("#account_id", "Choisir un compte");
                });
        });
    };
    const getRelatedTypes = () => {
        // console.log(clientSelect);
        $("#related_to").on("select2:select", (event) => {
            // console.log("Test");
            let relatedTo;

            relatedTo = event.params.data.element.value;
            // console.log(clientId);

            if (!relatedTo) {
                transactionTypeSelect.disabled = true;

                return;
            }
            transactionTypeSelect.disabled = false;
            viderSelect(transactionTypeSelect);
            axios
                .get(`/transactions/related-types/${relatedTo}`)
                .then((response) => {
                    console.log(response.data);

                    let options = response.data.transactionTypes;
                    console.log(options);

                    if (response.data.ok) {
                        options.forEach((transactionType) => {
                            let option = document.createElement("option");
                            option.value = transactionType.id;
                            option.text = `${transactionType.name}`;

                            transactionTypeSelect.add(option);
                        });
                    }
                    AppModules.initSelect2(
                        "#transaction_type_id",
                        "Choisir un type"
                    );
                });
        });
    };

    const initSelects = () => {
        accountSelect.disabled = true;
        transactionTypeSelect.disabled = true;
        viderSelect(accountSelect);
        viderSelect(transactionTypeSelect);
        AppModules.initSelect2("#client_id", "Choisir un Client");
        AppModules.initSelect2("#related_to", "Choisir");
    };
    // Fonction pour vider le select
    function viderSelect(select) {
        select.innerHTML = ""; // Supprime toutes les options du <select>
    }

    return {
        init: () => {
            transactionCreateForm = document.querySelector(
                ".addTransactionForm"
            );

            // Vérification que les formulaires existent
            if (!transactionCreateForm) {
                return;
            }
            accountSelect = transactionCreateForm.querySelector("#account_id");
            transactionTypeSelect = transactionCreateForm.querySelector(
                "#transaction_type_id"
            );
            AppModules.initDataTable("#operationTable");
            initSelects();
            getClientAccounts();
            getRelatedTypes();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppTransactionHistoryManager.init();
});
