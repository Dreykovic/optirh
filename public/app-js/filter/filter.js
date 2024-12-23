"use strict";

let AppGeneralFilterManager = (function () {
    let searchForm;
    let searchBtn;

    let modelUrl;

    let handleSearch = () => {
        modelUrl = searchForm.getAttribute("data-model-url");
        if (searchBtn) {
            searchBtn.addEventListener("click", (e) => {
                e.preventDefault();
                const formData = new FormData(searchForm);
                const search = formData.get("search");
                window.location.href = `${modelUrl}?search=${search}`;
            });
        }
    };

    let handleSort = () => {
        let orderBySelect = document.querySelector("#sortBy");

        if (orderBySelect) {
            orderBySelect.addEventListener("change", () => {
                // let url = new URL(window.location.href);
                // url.searchParams.set(orderBySelect.name, orderBySelect.value);
                // console.log(url);
                const params = new URLSearchParams(window.location.search);
                const name = orderBySelect.name;
                const value = orderBySelect.value;
                // Reset des paramètres existants
                params.delete(name);
                params.append(name, value);

                window.location.href =
                    window.location.pathname + "?" + params.toString();
            });
        }
        let sortOrderBtn = document.querySelector("#sortOrder");

        if (sortOrderBtn) {
            sortOrderBtn.addEventListener("click", () => {
                const order =
                    sortOrderBtn.getAttribute("data-sortOrder") === "asc"
                        ? "desc"
                        : "asc";
                const name = sortOrderBtn.getAttribute("data-name");

                const params = new URLSearchParams(window.location.search);

                // Reset des paramètres existants
                params.delete(name);
                params.append(name, order);
                window.location.href =
                    window.location.pathname + "?" + params.toString();
            });
        }
    };
    return {
        init: () => {
            searchForm = document.querySelector("#searchForm");

            // Vérification que les formulaires existent
            if (!searchForm) {
                return;
            }

            searchBtn = searchForm?.querySelector("#searchBtn");

            handleSearch();
            handleSort();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppGeneralFilterManager.init();
});
