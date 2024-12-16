if (typeof jQuery === "undefined") {
    throw new Error("jQuery plugins need to be before this file");
}

$(function () {
    "use strict";
    if ($(".montant").length > 0) {
        $(".montant").each(function (index, element) {
            let texte = AppModules.formatMontant($(element).text());

            $(element).text(texte + " FCFA");
            $(element).addClass("text-end");
        });
    }

    if ($("#phone").length > 0) {
        const countryData = window.intlTelInputGlobals.getCountryData();
        const addressDropdown = document.querySelector("#location");
        const input = document.querySelector("#phone");
        const iti = window.intlTelInput(input, {
            initialCountry: "tg",
            strictMode: true,
            nationalMode: true,
            utilsScript: "/assets/plugins/intl-tel-input/js/utils.js",
        });
        // populate the country dropdown
        for (let i = 0; i < countryData.length; i++) {
            const country = countryData[i];
            const optionNode = document.createElement("option");
            optionNode.value = country.iso2;
            const textNode = document.createTextNode(country.name);
            optionNode.appendChild(textNode);
            addressDropdown.appendChild(optionNode);
        }
        // set it's initial value
        addressDropdown.value = iti.getSelectedCountryData().iso2;

        // listen to the telephone input for changes
        input.addEventListener("countrychange", () => {
            addressDropdown.value = iti.getSelectedCountryData().iso2;
        });

        // listen to the address dropdown for changes
        addressDropdown.addEventListener("change", () => {
            iti.setCountry(addressDropdown.value);
        });
    }

    // end
});
