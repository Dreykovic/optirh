/**
 * Gestionnaire d'Authentification - OPTIRH
 * 
 * Module JavaScript qui gère la connexion des utilisateurs.
 * Utilise AJAX pour soumettre le formulaire de connexion et
 * redirige l'utilisateur selon ses permissions.
 * 
 * @author OPTIRH Team
 * @version 1.0
 * @requires AppModules (modules.js)
 */
"use strict";

/**
 * Module de gestion de l'authentification
 * Utilise le pattern Module révélant pour encapsuler la logique
 */
let AppAuthManager = (function () {
    // === VARIABLES PRIVÉES ===
    let loginForm;  // Référence au formulaire de connexion
    let loginBtn;   // Référence au bouton de soumission

    /**
     * Gère la soumission du formulaire de connexion
     * Configure les événements et traite les réponses AJAX
     * 
     * @private
     */
    const handleLogin = () => {
        // Récupération de l'URL de connexion depuis l'attribut data
        const loginUrl = loginForm.getAttribute("data-login-url");

        // Écouteur d'événement pour la soumission du formulaire
        loginForm.addEventListener("submit", async (e) => {
            // Empêcher la soumission normale du formulaire
            e.preventDefault();
            
            // Création de l'objet FormData avec les données du formulaire
            const formData = new FormData(loginForm);

            // Utilisation du module AppModules pour la soumission AJAX
            // avec un callback personnalisé pour gérer la réponse
            AppModules.submitFromBtn(
                loginBtn,        // Bouton à désactiver pendant la requête
                formData,        // Données à envoyer
                loginUrl,        // URL de destination
                loginCallback   // Fonction callback pour traiter la réponse
            );
        });
    };

    /**
     * Callback exécuté après une tentative de connexion réussie
     * Gère la redirection basée sur la réponse du serveur
     * 
     * @param {Object} response - Réponse JSON du serveur
     * @param {Object} response.data - Données de la réponse
     * @param {string} response.data.redirect - URL de redirection
     * @private
     */
    const loginCallback = (response) => {
        // Vérification de la présence d'une URL de redirection
        if (response.data && response.data.redirect) {
            // Redirection vers l'URL fournie par le serveur
            // (OptiHR, Recours, ou Gateway selon les permissions)
            window.location.href = response.data.redirect;
        } else {
            // Fallback : rechargement de la page si aucune redirection
            // n'est spécifiée (ne devrait normalement pas arriver)
            console.warn('Aucune URL de redirection fournie, rechargement de la page');
            window.location.reload();
        }
    };

    // === API PUBLIQUE ===
    return {
        /**
         * Initialise le gestionnaire d'authentification
         * Recherche les éléments DOM et configure les événements
         * 
         * @public
         */
        init: () => {
            // Recherche du formulaire de connexion dans le DOM
            loginForm = document.querySelector("#loginForm");

            // Vérification de l'existence du formulaire
            if (!loginForm) {
                console.warn('Formulaire de connexion introuvable (#loginForm)');
                return;
            }

            // Recherche du bouton de soumission
            loginBtn = loginForm.querySelector("#loginBtn");

            // Vérification de l'existence du bouton
            if (!loginBtn) {
                console.warn('Bouton de connexion introuvable (#loginBtn)');
                return;
            }

            // Configuration des gestionnaires d'événements
            handleLogin();
            
            console.log('Gestionnaire d\'authentification initialisé');
        },
    };
})();

// === INITIALISATION AUTOMATIQUE ===

/**
 * Initialisation automatique du module lors du chargement du DOM
 * Utilise DOMContentLoaded pour s'assurer que tous les éléments
 * sont disponibles avant l'initialisation
 */
document.addEventListener("DOMContentLoaded", (e) => {
    AppAuthManager.init();
});
