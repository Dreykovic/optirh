@extends('modules.opti-hr.pages.help.layout')

@php $currentSection = 'faq'; @endphp

@section('help-content')
<div class="help-section">
    <h1>9. Foire Aux Questions (FAQ)</h1>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Comment connaître mon solde de congés ?</div>
        <div class="guide-faq-answer">Votre solde est affiché en permanence dans la barre supérieure à côté du bouton "Demander Un Congés". Il est également visible dans le formulaire de demande d'absence.</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Puis-je modifier une demande d'absence déjà soumise ?</div>
        <div class="guide-faq-answer">Non, une fois soumise, une demande ne peut pas être modifiée. Vous pouvez cependant l'annuler et en créer une nouvelle tant qu'elle n'est pas complètement validée.</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Qui valide mes demandes d'absence ?</div>
        <div class="guide-faq-answer">Les demandes suivent un workflow hiérarchique : votre chef direct, puis le service GRH, puis la direction. Vous pouvez suivre l'avancement sur la page de liste des demandes.</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Comment obtenir une attestation de travail ?</div>
        <div class="guide-faq-answer">Rendez-vous dans Documents - Nouvelle demande, sélectionnez "Attestation de travail", puis soumettez votre demande. Le service RH traitera votre demande.</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Où trouver mes bulletins de paie ?</div>
        <div class="guide-faq-answer">Accédez à Mon Espace - Mes bulletins ou cliquez sur votre nom en haut à droite puis "Bulletins de paie".</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Comment changer mon mot de passe ?</div>
        <div class="guide-faq-answer">Contactez le service RH ou utilisez la fonctionnalité "Mot de passe oublié" sur la page de connexion.</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Que signifient les différents statuts de demande ?</div>
        <div class="guide-faq-answer">"En attente" = en cours de validation, "IN_PROGRESS" = traitement en cours, "Approuvée" = validée, "Annulée" = annulée par vous.</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Comment contacter le support ?</div>
        <div class="guide-faq-answer">Cliquez sur "Aide" dans le menu latéral ou sur "Get Help" dans la barre supérieure pour accéder au Centre d'Aide.</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Le mode sombre affecte-t-il mes données ?</div>
        <div class="guide-faq-answer">Non, le mode sombre est purement visuel et n'affecte aucune de vos données. Il peut être activé/désactivé à tout moment.</div>
    </div>

    <div class="guide-faq-item">
        <div class="guide-faq-question">Puis-je accéder à OptiHR depuis mon téléphone ?</div>
        <div class="guide-faq-answer">Oui, l'application est accessible depuis tout navigateur web, y compris sur mobile. L'interface s'adapte à la taille de votre écran.</div>
    </div>
</div>
@endsection
