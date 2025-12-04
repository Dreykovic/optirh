@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'problemes'; @endphp

@section('help-content')
<div class="help-section">
    <h1>10. Résolution de problèmes / Astuces</h1>

    <h2>Problèmes de connexion</h2>
    <table class="guide-table">
        <tr>
            <th>Problème</th>
            <th>Solution</th>
        </tr>
        <tr>
            <td>Mot de passe incorrect</td>
            <td>Vérifiez les majuscules. Utilisez "Mot de passe oublié?" si nécessaire.</td>
        </tr>
        <tr>
            <td>Session expirée</td>
            <td>Reconnectez-vous. Les sessions expirent après une période d'inactivité.</td>
        </tr>
        <tr>
            <td>Page blanche après connexion</td>
            <td>Videz le cache du navigateur (<span class="guide-kbd">Ctrl</span>+<span class="guide-kbd">Shift</span>+<span class="guide-kbd">Suppr</span>)</td>
        </tr>
        <tr>
            <td>Compte désactivé</td>
            <td>Contactez le service RH pour réactiver votre compte.</td>
        </tr>
    </table>

    <h2>Problèmes d'affichage</h2>
    <table class="guide-table">
        <tr>
            <th>Problème</th>
            <th>Solution</th>
        </tr>
        <tr>
            <td>Interface mal affichée</td>
            <td>Actualisez la page (<span class="guide-kbd">F5</span>) ou videz le cache.</td>
        </tr>
        <tr>
            <td>Boutons non cliquables</td>
            <td>Désactivez les bloqueurs de publicité ou extensions pour ce site.</td>
        </tr>
        <tr>
            <td>Calendrier ne s'affiche pas</td>
            <td>Vérifiez que JavaScript est activé dans votre navigateur.</td>
        </tr>
    </table>

    <h2>Problèmes avec les demandes</h2>
    <table class="guide-table">
        <tr>
            <th>Problème</th>
            <th>Solution</th>
        </tr>
        <tr>
            <td>Impossible de soumettre</td>
            <td>Vérifiez que tous les champs obligatoires (*) sont remplis.</td>
        </tr>
        <tr>
            <td>Fichier joint refusé</td>
            <td>Vérifiez le format (PDF, JPG, PNG) et la taille (max 5 Mo).</td>
        </tr>
        <tr>
            <td>Demande bloquée</td>
            <td>Contactez votre supérieur hiérarchique ou le GRH.</td>
        </tr>
    </table>

    <h2>Astuces pour une utilisation optimale</h2>

    <div class="guide-tip">
        Utilisez le bouton "Demander Un Congés" en haut de page pour un accès rapide au formulaire de demande d'absence.
    </div>

    <div class="guide-tip">
        Consultez régulièrement le tableau de bord pour voir les demandes en attente de votre validation.
    </div>

    <div class="guide-tip">
        Activez le mode sombre pour réduire la fatigue oculaire lors d'une utilisation prolongée.
    </div>

    <div class="guide-tip">
        Utilisez la fonction de recherche dans les tableaux pour trouver rapidement une information spécifique.
    </div>

    <div class="guide-tip">
        Gardez vos informations personnelles à jour dans "Mes informations" pour faciliter la communication RH.
    </div>

    <h2>Raccourcis utiles</h2>
    <table class="guide-table">
        <tr>
            <th>Action</th>
            <th>Raccourci</th>
        </tr>
        <tr>
            <td>Actualiser la page</td>
            <td><span class="guide-kbd">F5</span> ou <span class="guide-kbd">Ctrl</span>+<span class="guide-kbd">R</span></td>
        </tr>
        <tr>
            <td>Rechercher dans la page</td>
            <td><span class="guide-kbd">Ctrl</span>+<span class="guide-kbd">F</span></td>
        </tr>
        <tr>
            <td>Retour page précédente</td>
            <td><span class="guide-kbd">Alt</span>+<span class="guide-kbd">Flèche gauche</span></td>
        </tr>
    </table>

    <h2>Contact Support</h2>
    <p>Si vous rencontrez un problème non résolu par ce guide :</p>
    <ul>
        <li>Consultez la page <strong>Aide</strong> dans le menu latéral</li>
        <li>Contactez le service RH de votre organisation</li>
        <li>Décrivez précisément le problème avec des captures d'écran si possible</li>
    </ul>
</div>
@endsection
