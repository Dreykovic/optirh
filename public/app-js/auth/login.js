"use strict";

let AppAuthManager = (function () {
    let loginForm;
    let loginBtn;

    const handleLogin = () => {
        const loginUrl = loginForm.getAttribute("data-login-url");

        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);

            // Utilisons votre module AppModules mais avec un callback personnalisé
            AppModules.submitFromBtn(
                loginBtn,
                formData,
                loginUrl,
                loginCallback
            );
        });
    };

    // Callback modifié pour gérer la redirection basée sur la réponse du serveur
    const loginCallback = (response) => {
        // Rediriger vers l'URL fournie par le serveur
        if (response.data.redirect) {
            window.location.href = response.data.redirect;
        } else {
            // Fallback au cas où aucune URL de redirection n'est fournie
            window.location.reload();
        }
    };

    return {
        init: () => {
            loginForm = document.querySelector("#loginForm");

            if (!loginForm) {
                return;
            }

            loginBtn = loginForm.querySelector("#loginBtn");

            handleLogin();
        },
    };
})();

document.addEventListener("DOMContentLoaded", (e) => {
    AppAuthManager.init();
});
