@extends('modules.opti-hr.pages.base')
@section('plugins-style')
    <link rel="stylesheet" href="{{ asset('assets/css/help-page.css') }}">
@endsection

@section('admin-content')
<div class="guide-container">

    <!-- EN-TÊTE -->
    <div class="guide-header">
        <h1>Guide Utilisateur OptiHR</h1>
        <p>Plateforme de Gestion des Ressources Humaines - ARCOP MAN</p>
    </div>

    <!-- NAVIGATION -->
    <nav class="guide-nav">
        <div class="guide-nav-title">Table des matières</div>
        <ul>
            <li><a href="#introduction">1. Introduction</a></li>
            <li><a href="#prise-en-main">2. Prise en main rapide</a></li>
            <li><a href="#tableau-de-bord">3. Tableau de bord</a></li>
            <li><a href="#absences">4. Gestion des absences</a></li>
            <li><a href="#documents">5. Gestion des documents</a></li>
            <li><a href="#personnel">6. Administration du personnel</a></li>
            <li><a href="#espace-collaboratif">7. Espace Collaboratif</a></li>
            <li><a href="#mon-espace">8. Mon Espace</a></li>
            <li><a href="#faq">9. FAQ</a></li>
            <li><a href="#problemes">10. Résolution de problèmes</a></li>
        </ul>
    </nav>

    <!-- SECTION 1: INTRODUCTION -->
    <section class="guide-section" id="introduction">
        <h2>1. Introduction / À propos</h2>

        <h3>Qu'est-ce qu'OptiHR ?</h3>
        <p>OptiHR est une application de gestion des ressources humaines intégrée à la plateforme ARCOP MAN. Elle permet de centraliser et simplifier la gestion du personnel, des absences, des documents administratifs et de la communication interne.</p>

        <h3>Fonctionnalités principales</h3>
        <table class="guide-table">
            <tr>
                <th>Module</th>
                <th>Description</th>
            </tr>
            <tr>
                <td><strong>Gestion des absences</strong></td>
                <td>Demandes de congés, validation hiérarchique, suivi du solde</td>
            </tr>
            <tr>
                <td><strong>Documents RH</strong></td>
                <td>Demandes d'attestations, certificats de travail, documents officiels</td>
            </tr>
            <tr>
                <td><strong>Administration</strong></td>
                <td>Gestion des employés, directions, contrats et identifiants</td>
            </tr>
            <tr>
                <td><strong>Espace Collaboratif</strong></td>
                <td>Publications internes, partage d'informations</td>
            </tr>
            <tr>
                <td><strong>Bulletins de paie</strong></td>
                <td>Consultation et téléchargement des fiches de paie</td>
            </tr>
        </table>

        <h3>Rôles utilisateurs</h3>
        <table class="guide-table">
            <tr>
                <th>Rôle</th>
                <th>Code</th>
                <th>Permissions</th>
            </tr>
            <tr>
                <td>Directeur Général</td>
                <td><span class="guide-badge guide-badge-success">DG</span></td>
                <td>Validation finale, accès complet</td>
            </tr>
            <tr>
                <td>Directeur (DSAF, etc.)</td>
                <td><span class="guide-badge guide-badge-info">DSAF</span></td>
                <td>Validation hiérarchique, gestion d'équipe</td>
            </tr>
            <tr>
                <td>Gestionnaire RH</td>
                <td><span class="guide-badge guide-badge-warning">GRH</span></td>
                <td>Administration complète du personnel et des demandes</td>
            </tr>
            <tr>
                <td>Employé</td>
                <td><span class="guide-badge guide-badge-info">—</span></td>
                <td>Consultation, soumission de demandes</td>
            </tr>
        </table>
    </section>

    <!-- SECTION 2: PRISE EN MAIN -->
    <section class="guide-section" id="prise-en-main">
        <h2>2. Prise en main rapide</h2>

        <h3>Connexion à l'application</h3>
        <div class="guide-step">
            <span class="guide-step-number">1</span>
            Accédez à la page de connexion ARCOP MAN
        </div>
        <div class="guide-step">
            <span class="guide-step-number">2</span>
            Entrez votre <strong>Adresse Email</strong> professionnelle
        </div>
        <div class="guide-step">
            <span class="guide-step-number">3</span>
            Saisissez votre <strong>Mot de passe</strong>
        </div>
        <div class="guide-step">
            <span class="guide-step-number">4</span>
            Cochez <em>"Se souvenir de moi"</em> si vous êtes sur un appareil personnel
        </div>
        <div class="guide-step">
            <span class="guide-step-number">5</span>
            Cliquez sur <strong>Se connecter</strong>
        </div>

        <div class="guide-tip">
            En cas d'oubli de mot de passe, cliquez sur "Mot de passe oublié?" pour recevoir un lien de réinitialisation par email.
        </div>

        <h3>Navigation dans l'interface</h3>
        <p>L'interface est composée de plusieurs zones :</p>

        <h4>Barre latérale gauche (Menu principal)</h4>
        <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-home"></i></span><strong>Tableau de bord</strong> - Vue d'ensemble et statistiques</div>
        <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-ui-messaging"></i></span><strong>Espace Collaboratif</strong> - Publications et annonces</div>
        <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-calendar"></i></span><strong>Absences</strong> - Demandes de congés</div>
        <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-file-document"></i></span><strong>Documents</strong> - Attestations et certificats</div>
        <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-key"></i></span><strong>Identifiants</strong> - Gestion des comptes (Admin)</div>
        <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-users-alt-5"></i></span><strong>Personnel</strong> - Employés et contrats (Admin)</div>
        <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-user"></i></span><strong>Mes Données</strong> - Informations personnelles</div>
        <div class="guide-menu-item"><span class="guide-menu-icon"><i class="icofont-question-circle"></i></span><strong>Aide</strong> - Centre d'assistance</div>

        <h4>Barre supérieure</h4>
        <p>Contient le bouton d'action rapide <strong>"Demander Un Congés"</strong>, l'affichage du solde de congés et le menu utilisateur avec votre profil.</p>

        <h3>Mode Sombre</h3>
        <p>Activez le mode sombre via le commutateur <strong>"Mode Sombre"</strong> dans la barre latérale pour un affichage plus confortable en conditions de faible luminosité.</p>

        <h3>Portail d'Applications</h3>
        <p>Le lien <strong>"Portail d'Applications"</strong> en bas de la barre latérale vous permet de retourner au portail principal ARCOP MAN pour accéder aux autres applications.</p>
    </section>

    <!-- SECTION 3: TABLEAU DE BORD -->
    <section class="guide-section" id="tableau-de-bord">
        <h2>3. Tableau de bord</h2>

        <h3>Vue d'ensemble</h3>
        <p>Le tableau de bord est votre page d'accueil. Il présente une synthèse de l'activité RH de votre organisation.</p>

        <h3>Actions rapides</h3>
        <p>Quatre boutons d'accès direct aux actions les plus fréquentes :</p>
        <table class="guide-table">
            <tr>
                <th>Bouton</th>
                <th>Action</th>
            </tr>
            <tr>
                <td><span class="guide-badge guide-badge-success">Ajouter un employé</span></td>
                <td>Accéder à la liste des employés pour en ajouter</td>
            </tr>
            <tr>
                <td><span class="guide-badge guide-badge-info">Ajouter un département</span></td>
                <td>Créer une nouvelle direction/département</td>
            </tr>
            <tr>
                <td><span class="guide-badge guide-badge-warning">Enregistrer une absence</span></td>
                <td>Soumettre une nouvelle demande de congé</td>
            </tr>
            <tr>
                <td><span class="guide-badge guide-badge-info">Nouvelle publication</span></td>
                <td>Publier une information dans l'espace collaboratif</td>
            </tr>
        </table>

        <h3>Indicateurs clés (KPIs)</h3>
        <table class="guide-table">
            <tr>
                <th>Indicateur</th>
                <th>Description</th>
            </tr>
            <tr>
                <td>Taux d'absentéisme</td>
                <td>Pourcentage d'absences sur la période</td>
            </tr>
            <tr>
                <td>Temps moyen traitement</td>
                <td>Durée moyenne de traitement des demandes</td>
            </tr>
            <tr>
                <td>Taux d'approbation</td>
                <td>Pourcentage de demandes approuvées</td>
            </tr>
        </table>

        <h3>Statistiques rapides</h3>
        <p>Cartes affichant les données essentielles avec évolution par rapport au mois précédent :</p>
        <ul>
            <li><strong>Total employés</strong> - Nombre d'employés actifs</li>
            <li><strong>Départements</strong> - Nombre de directions/services</li>
            <li><strong>Absences en attente</strong> - Demandes à traiter</li>
            <li><strong>Documents en attente</strong> - Demandes de documents à traiter</li>
        </ul>

        <h3>Calendrier des absences</h3>
        <p>Visualisation mensuelle des absences planifiées. Fonctionnalités disponibles :</p>
        <ul>
            <li><strong>Navigation</strong> : Boutons pour changer de mois</li>
            <li><strong>Aujourd'hui</strong> : Revenir à la date du jour</li>
            <li><strong>Vue Mois/Liste</strong> : Basculer entre l'affichage calendrier et liste</li>
            <li><strong>Filtre "Toutes les absences"</strong> : Afficher/masquer toutes les absences</li>
        </ul>

        <h3>Anniversaires à venir</h3>
        <p>Liste des anniversaires des employés dans les prochains jours.</p>

        <h3>Graphiques de répartition</h3>
        <ul>
            <li><strong>Répartition par département</strong> : Diagramme des effectifs par direction</li>
            <li><strong>Répartition par genre</strong> : Diagramme Homme/Femme</li>
        </ul>

        <h3>Tableaux récapitulatifs</h3>
        <p><strong>Demandes d'absence récentes</strong> : Liste des dernières demandes avec employé, type, période, jours, statut et lien vers les détails.</p>
        <p><strong>Demandes de documents</strong> : Liste des dernières demandes de documents avec type, date et statut.</p>
        <p><strong>Publications récentes</strong> : Dernières publications de l'espace collaboratif.</p>

        <h3>Bouton Actualiser</h3>
        <p>Cliquez sur le bouton <strong>Actualiser</strong> en haut à droite pour rafraîchir les données du tableau de bord.</p>
    </section>

    <!-- SECTION 4: GESTION DES ABSENCES -->
    <section class="guide-section" id="absences">
        <h2>4. Gestion des absences</h2>

        <h3>4.1 Soumettre une demande d'absence</h3>

        <div class="guide-step">
            <span class="guide-step-number">1</span>
            Accédez à <strong>Absences - Soumettre une demande</strong> ou cliquez sur <strong>"Demander Un Congés"</strong> dans la barre supérieure
        </div>
        <div class="guide-step">
            <span class="guide-step-number">2</span>
            <strong>Type d'absence</strong> : Sélectionnez le motif dans la liste (annuel, maternité, exceptionnel)
        </div>
        <div class="guide-step">
            <span class="guide-step-number">3</span>
            <strong>Période d'absence</strong> : Choisissez la date de début et la date de fin
        </div>
        <div class="guide-step">
            <span class="guide-step-number">4</span>
            <strong>Adresse pendant l'absence</strong> : Indiquez où vous serez joignable
        </div>
        <div class="guide-step">
            <span class="guide-step-number">5</span>
            <strong>Motif</strong> (optionnel) : Expliquez brièvement la raison (max 1000 caractères)
        </div>
        <div class="guide-step">
            <span class="guide-step-number">6</span>
            <strong>Justificatif</strong> (optionnel) : Joignez un document (PDF, JPG, PNG - max 5 Mo)
        </div>
        <div class="guide-step">
            <span class="guide-step-number">7</span>
            Vérifiez le <strong>Résumé de votre demande</strong> puis cliquez sur <strong>Soumettre la demande</strong>
        </div>

        <div class="guide-info">
            Votre solde de congés disponible est affiché en haut de la page. Le nombre de jours ouvrés est calculé automatiquement selon les dates sélectionnées.
        </div>

        <h3>4.2 Types d'absences</h3>
        <table class="guide-table">
            <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Déductible du solde</th>
            </tr>
            <tr>
                <td>Annuel</td>
                <td>Congés payés annuels</td>
                <td><span class="guide-badge guide-badge-success">Oui</span></td>
            </tr>
            <tr>
                <td>Maternité</td>
                <td>Congé accordé aux salariées enceintes</td>
                <td><span class="guide-badge guide-badge-danger">Non</span></td>
            </tr>
            <tr>
                <td>Exceptionnel</td>
                <td>Absence pour raison spécifique</td>
                <td><span class="guide-badge guide-badge-danger">Non</span></td>
            </tr>
        </table>

        <h3>4.3 Liste des demandes</h3>
        <p>Accédez à <strong>Absences - Liste des demandes</strong> pour voir toutes vos demandes et celles à traiter.</p>

        <h4>Filtres disponibles</h4>
        <ul>
            <li><strong>Toutes</strong> : Toutes les demandes</li>
            <li><strong>Mes demandes</strong> : Vos propres demandes uniquement</li>
            <li><strong>À valider</strong> : Demandes en attente de votre validation</li>
        </ul>

        <h4>Onglets de statut</h4>
        <ul>
            <li><strong>À traiter</strong> : Demandes actives en cours de traitement</li>
            <li><strong>Historique</strong> : Demandes terminées (approuvées/rejetées)</li>
            <li><strong>Annulées</strong> : Demandes annulées</li>
        </ul>

        <h3>4.4 Workflow de validation</h3>
        <p>Chaque demande d'absence suit un processus de validation en plusieurs étapes :</p>
        <div class="guide-workflow">
            <span class="guide-workflow-step">Demande</span>
            <span class="guide-workflow-arrow"><i class="icofont-arrow-right"></i></span>
            <span class="guide-workflow-step">Chef Direct</span>
            <span class="guide-workflow-arrow"><i class="icofont-arrow-right"></i></span>
            <span class="guide-workflow-step">GRH</span>
            <span class="guide-workflow-arrow"><i class="icofont-arrow-right"></i></span>
            <span class="guide-workflow-step">Direction</span>
        </div>

        <h4>Statuts possibles</h4>
        <table class="guide-table">
            <tr>
                <th>Statut</th>
                <th>Signification</th>
            </tr>
            <tr>
                <td><span class="guide-badge guide-badge-warning">En attente</span></td>
                <td>En attente de validation à une étape</td>
            </tr>
            <tr>
                <td><span class="guide-badge guide-badge-info">IN_PROGRESS</span></td>
                <td>En cours de traitement</td>
            </tr>
            <tr>
                <td><span class="guide-badge guide-badge-success">Approuvée</span></td>
                <td>Demande validée par tous les niveaux</td>
            </tr>
            <tr>
                <td><span class="guide-badge guide-badge-danger">Annulée</span></td>
                <td>Demande annulée par l'employé</td>
            </tr>
        </table>

        <h3>4.5 Annuler une demande</h3>
        <p>Vous pouvez annuler une demande tant qu'elle n'est pas complètement validée en cliquant sur le bouton <strong>Annuler</strong> rouge dans la liste des demandes.</p>

        <h3>4.6 Décisions annuelles</h3>
        <p>La section <strong>Absences - Décisions annuelles</strong> permet aux administrateurs de gérer les décisions officielles relatives aux congés annuels avec numéro de référence, année et date d'application.</p>
    </section>

    <!-- SECTION 5: GESTION DES DOCUMENTS -->
    <section class="guide-section" id="documents">
        <h2>5. Gestion des documents</h2>

        <h3>5.1 Demander un document</h3>
        <div class="guide-step">
            <span class="guide-step-number">1</span>
            Accédez à <strong>Documents - Nouvelle demande</strong>
        </div>
        <div class="guide-step">
            <span class="guide-step-number">2</span>
            Sélectionnez le <strong>Type de document</strong> souhaité dans la liste
        </div>
        <div class="guide-step">
            <span class="guide-step-number">3</span>
            Définissez la <strong>Période du document</strong> (date de début et fin si applicable)
        </div>
        <div class="guide-step">
            <span class="guide-step-number">4</span>
            Cliquez sur <strong>Soumettre la demande</strong>
        </div>

        <h3>5.2 Types de documents disponibles</h3>
        <table class="guide-table">
            <tr>
                <th>Document</th>
                <th>Description</th>
            </tr>
            <tr>
                <td>Attestation de Stage</td>
                <td>Attestation remise en fin de stage</td>
            </tr>
            <tr>
                <td>Attestation de travail</td>
                <td>Certificat attestant de votre emploi</td>
            </tr>
        </table>

        <h3>5.3 Suivi des demandes</h3>
        <p>Accédez à <strong>Documents - Mes documents</strong> pour consulter l'état de vos demandes.</p>

        <h4>Filtres disponibles</h4>
        <ul>
            <li><strong>Toutes</strong> : Toutes les demandes</li>
            <li><strong>À Traiter</strong> : Demandes en attente de traitement</li>
            <li><strong>Terminées</strong> : Documents prêts</li>
        </ul>

        <div class="guide-tip">
            Contactez le service RH pour toute question sur les documents disponibles.
        </div>
    </section>

    <!-- SECTION 6: ADMINISTRATION DU PERSONNEL -->
    <section class="guide-section" id="personnel">
        <h2>6. Administration du personnel</h2>
        <div class="guide-info">Cette section est réservée aux utilisateurs ayant des droits d'administration (GRH, Directeurs).</div>

        <h3>6.1 Gestion des identifiants</h3>
        <p>Accès : <strong>Administration - Identifiants</strong></p>

        <h4>Fonctionnalités</h4>
        <ul>
            <li><strong>Ajouter</strong> : Créer un nouveau compte utilisateur</li>
            <li><strong>Filtrer</strong> : Toutes, Activés, Non Activés</li>
            <li><strong>Rechercher</strong> : Trouver un utilisateur par nom ou email</li>
        </ul>

        <h4>Informations affichées</h4>
        <ul>
            <li>Membre (nom complet)</li>
            <li>Username (identifiant de connexion)</li>
            <li>Email</li>
            <li>Date d'intégration</li>
            <li>Rôle (DG, DSAF, GRH, etc.)</li>
            <li>Status (Activé/Désactivé)</li>
        </ul>

        <h3>6.2 Gestion des directions</h3>
        <p>Accès : <strong>Personnel - Directions</strong></p>

        <h4>Actions possibles</h4>
        <ul>
            <li><strong>Ajouter</strong> : Créer une nouvelle direction/département</li>
            <li><strong>Actions</strong> : Modifier ou supprimer une direction existante</li>
        </ul>

        <h4>Informations d'une direction</h4>
        <ul>
            <li>Directeur assigné</li>
            <li>Code de la direction (DG, DSAF, etc.)</li>
            <li>Définition/Description</li>
        </ul>

        <h3>6.3 Gestion des membres (Employés)</h3>
        <p>Accès : <strong>Personnel - Membres</strong></p>

        <h4>Liste des employés</h4>
        <p>Affiche tous les employés avec : Nom, Contact, Email, Adresse.</p>

        <h4>Actions disponibles</h4>
        <ul>
            <li><strong>Ajouter</strong> : Enregistrer un nouvel employé</li>
            <li><strong>Directions</strong> : Filtrer par direction</li>
            <li><strong>Menu Actions</strong> : Modifier, voir les détails, supprimer</li>
        </ul>

        <h3>6.4 Gestion des contrats</h3>
        <p>Accès : <strong>Personnel - Contrats</strong></p>

        <h4>Filtres par statut</h4>
        <ul>
            <li><strong>En Cours</strong> : Contrats actifs</li>
            <li><strong>Suspendus</strong> : Contrats temporairement suspendus</li>
            <li><strong>Terminés</strong> : Contrats arrivés à échéance</li>
            <li><strong>Démissionnés</strong> : Employés ayant démissionné</li>
            <li><strong>Licenciés</strong> : Contrats résiliés</li>
            <li><strong>Supprimés</strong> : Contrats archivés</li>
        </ul>

        <h4>Informations d'un contrat</h4>
        <ul>
            <li>Employé</li>
            <li>Direction</li>
            <li>Poste</li>
            <li>Date d'embauche</li>
            <li>Type (Full-Time, Part-Time, etc.)</li>
            <li>Solde de congés</li>
        </ul>

        <h3>6.5 Bulletins de paie (Administration)</h3>
        <p>Accès : <strong>Personnel - Bulletins de paie</strong></p>

        <p>Cette page permet d'importer les bulletins de paie depuis Sage Paie :</p>
        <div class="guide-step">
            <span class="guide-step-number">1</span>
            Cliquez sur <strong>Choose File</strong> pour sélectionner le fichier
        </div>
        <div class="guide-step">
            <span class="guide-step-number">2</span>
            Vérifiez le fichier sélectionné
        </div>
        <div class="guide-step">
            <span class="guide-step-number">3</span>
            Cliquez sur <strong>ENVOYER</strong> pour distribuer les bulletins
        </div>

        <div class="guide-warning">
            Le fichier doit être au format compatible Sage Paie pour être traité correctement.
        </div>
    </section>

    <!-- SECTION 7: ESPACE COLLABORATIF -->
    <section class="guide-section" id="espace-collaboratif">
        <h2>7. Espace Collaboratif</h2>

        <p>L'Espace Collaboratif est un outil de communication interne permettant de partager des notes et informations avec l'ensemble du personnel.</p>

        <h3>7.1 Consulter les publications</h3>
        <p>Accédez à <strong>Espace Collaboratif</strong> dans le menu principal.</p>

        <h4>Filtres disponibles</h4>
        <ul>
            <li><strong>Toutes</strong> : Toutes les publications</li>
            <li><strong>Publiées</strong> : Publications visibles par tous</li>
            <li><strong>En attente</strong> : Publications en attente de validation</li>
            <li><strong>Archivées</strong> : Publications archivées</li>
        </ul>

        <h3>7.2 Créer une publication</h3>
        <div class="guide-step">
            <span class="guide-step-number">1</span>
            Dans le champ en bas de page, saisissez le <strong>Titre de votre publication</strong> (max 255 caractères)
        </div>
        <div class="guide-step">
            <span class="guide-step-number">2</span>
            Ajoutez le contenu de votre message
        </div>
        <div class="guide-step">
            <span class="guide-step-number">3</span>
            Optionnellement, joignez des fichiers
        </div>
        <div class="guide-step">
            <span class="guide-step-number">4</span>
            Publiez votre message
        </div>

        <h3>7.3 Gérer les publications</h3>
        <p>Chaque publication dispose d'un menu <strong>Actions</strong> permettant de :</p>
        <ul>
            <li>Modifier le contenu</li>
            <li>Archiver la publication</li>
            <li>Supprimer la publication</li>
        </ul>

        <h3>7.4 Fichiers attachés</h3>
        <p>Les publications peuvent contenir des fichiers joints (PDF, images, etc.) téléchargeables en cliquant sur l'icône de téléchargement à côté du nom du fichier.</p>
    </section>

    <!-- SECTION 8: MON ESPACE -->
    <section class="guide-section" id="mon-espace">
        <h2>8. Mon Espace</h2>

        <h3>8.1 Mes informations</h3>
        <p>Accès : <strong>Mon Espace - Mes Données - Mes informations</strong> ou via le menu utilisateur <strong>Paramètres Profil</strong></p>

        <h4>Section Identité</h4>
        <ul>
            <li>Nom et Prénoms</li>
            <li>Nationalité</li>
            <li>Religion</li>
            <li>Situation matrimoniale</li>
            <li>Genre</li>
            <li>Date de naissance</li>
        </ul>

        <h4>Section Adresse</h4>
        <ul>
            <li>Email professionnel</li>
            <li>Contact téléphonique</li>
            <li>Contact d'urgence</li>
            <li>Adresse complète (rue, ville, quartier)</li>
        </ul>

        <h4>Section Compte Banque</h4>
        <ul>
            <li>Nom de la banque</li>
            <li>Numéro de compte</li>
            <li>IBAN et Swift</li>
            <li>Clé RIB, Code Banque, Code Guichet</li>
        </ul>

        <p>Cliquez sur <strong>ENREGISTRER</strong> pour sauvegarder vos modifications.</p>

        <h3>8.2 Mes bulletins de paie</h3>
        <p>Accès : <strong>Mon Espace - Mes Données - Mes bulletins</strong> ou via le menu utilisateur</p>

        <p>Cette page liste tous vos bulletins de paie disponibles. Utilisez la recherche et la pagination pour trouver un bulletin spécifique.</p>

        <h3>8.3 Menu utilisateur</h3>
        <p>Cliquez sur votre nom en haut à droite pour accéder au menu rapide :</p>
        <ul>
            <li><strong>Bulletins de paie</strong> : Accès direct à vos fiches de paie</li>
            <li><strong>Paramètres Profil</strong> : Modifier vos informations</li>
            <li><strong>Se Déconnecter</strong> : Fermer votre session</li>
        </ul>

        <div class="guide-tip">
            Pensez à vous déconnecter après chaque utilisation sur un ordinateur partagé.
        </div>
    </section>

    <!-- SECTION 9: FAQ -->
    <section class="guide-section" id="faq">
        <h2>9. Foire Aux Questions (FAQ)</h2>

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
    </section>

    <!-- SECTION 10: RÉSOLUTION DE PROBLÈMES -->
    <section class="guide-section" id="problemes">
        <h2>10. Résolution de problèmes / Astuces</h2>

        <h3>Problèmes de connexion</h3>
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

        <h3>Problèmes d'affichage</h3>
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

        <h3>Problèmes avec les demandes</h3>
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

        <h3>Astuces pour une utilisation optimale</h3>

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

        <h3>Raccourcis utiles</h3>
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

        <h3>Contact Support</h3>
        <p>Si vous rencontrez un problème non résolu par ce guide :</p>
        <ul>
            <li>Consultez la page <strong>Aide</strong> dans le menu latéral</li>
            <li>Contactez le service RH de votre organisation</li>
            <li>Décrivez précisément le problème avec des captures d'écran si possible</li>
        </ul>
    </section>

    <!-- FOOTER -->
    <div class="guide-footer">
        <p>&copy; {{ date('Y') }} ARCOP - OptiHR | Guide Utilisateur v1.0</p>
        <p><em>"Simplicité, efficacité, performance."</em></p>
    </div>

</div>
@endsection

@push('plugins-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation d'entrée pour les sections
            const sections = document.querySelectorAll('.guide-section');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            sections.forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'all 0.5s ease';
                observer.observe(section);
            });

            // Smooth scroll pour la navigation
            document.querySelectorAll('.guide-nav a').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
@endpush
