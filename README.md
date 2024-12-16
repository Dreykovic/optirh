# micro_manager

Projet de gestion de microfinance

# Documentation des Utilisateurs et Accès

## Introduction

Cette documentation fournit des informations sur les utilisateurs créés dans la base de données, leurs rôles, et les détails de connexion associés. Elle est destinée à aider les nouveaux utilisateurs à accéder et utiliser la plateforme.

## Liste des Utilisateurs

### 1. **Accountant**

-   **Nom complet** : Accountant Accountant
-   **Nom d'utilisateur** : Accountant
-   **Email** : [accountant@micro.com](mailto:accountant@micro.com)
-   **Mot de passe** : accountant
-   **Rôle** : Accountant
-   **Profil** : Employee
-   **Date de naissance** : Aujourd'hui

### 2. **Cashier**

-   **Nom complet** : Cashier Cashier
-   **Nom d'utilisateur** : Cashier
-   **Email** : [cashier@micro.com](mailto:cashier@micro.com)
-   **Mot de passe** : cashier
-   **Rôle** : Cashier
-   **Profil** : Employee
-   **Date de naissance** : Aujourd'hui

### 3. **Admin**

-   **Nom complet** : Admin Admin
-   **Nom d'utilisateur** : Admin
-   **Email** : [admin@micro.com](mailto:admin@micro.com)
-   **Mot de passe** : admin
-   **Rôle** : Admin
-   **Profil** : Employee
-   **Date de naissance** : Aujourd'hui

### 4. **Boss**

-   **Nom complet** : Boss Boss
-   **Nom d'utilisateur** : Boss
-   **Email** : [boss@micro.com](mailto:boss@micro.com)
-   **Mot de passe** : boss
-   **Rôle** : Boss
-   **Profil** : Employee
-   **Date de naissance** : Aujourd'hui

### 5. **Clients**

Cinq utilisateurs clients ont été créés à l'aide de la factory. Ils sont attribués au rôle de client. Les informations spécifiques sur ces utilisateurs ne sont pas fournies ici.

## Accès et Rôles

-   **Admin** : Accès complet à toutes les fonctionnalités administratives de la plateforme.
-   **Boss** : Accès aux fonctionnalités de gestion et supervision.
-   **Cashier** : Accès aux fonctionnalités liées à la caisse et aux transactions.
-   **Accountant** : Accès aux fonctionnalités comptables et de gestion des finances.
-   **Client** : Accès limité aux fonctionnalités destinées aux utilisateurs finaux.

## Utilisation des Identifiants

Pour vous connecter à la plateforme, utilisez les identifiants suivants :

1. **Email** : Utilisez l'adresse mail spécifié pour chaque rôle.
2. **Mot de passe** : Utilisez le mot de passe fourni pour chaque utilisateur.

---

Pour toute question ou problème concernant les connexions ou les rôles, veuillez contacter le support technique.

# Documentation des états

## États d'un **compte (Account)**

1. **Active** : Le compte est actif et opérationnel.
2. **Inactive** : Le compte est inactif, mais peut être réactivé.
3. **Suspended** : Le compte a été temporairement suspendu (peut-être en raison d'une violation de conditions ou d'un autre problème).
4. **Closed** : Le compte a été définitivement fermé par l'utilisateur ou l'institution.
5. **Pending Activation** : Le compte a été créé mais n'est pas encore activé (en attente de validation ou de vérification).
6. **Pending Closure** : Le compte est en cours de fermeture mais n'a pas encore été complètement fermé.
7. **Under Review** : Le compte est sous révision pour des raisons administratives ou de sécurité.
8. **Blocked** : Le compte est bloqué, souvent pour des raisons de sécurité ou de fraude.
9. **Frozen** : Les opérations sur le compte sont temporairement interrompues (généralement pour des raisons légales ou de vérification).
10. **Terminated** : Le compte a été définitivement désactivé et ne peut plus être utilisé.

---

## États d'un **compte de crédit (Credit Account)**

1. **Active** : Le compte de crédit est en cours d'utilisation et en bon état.
2. **In Good Standing** : Le titulaire du compte effectue régulièrement les paiements, et le compte est en bon état.
3. **Overdue** : Il y a des paiements en retard sur le compte.
4. **Delinquent** : Le compte de crédit est en défaut de paiement pendant une certaine période (souvent plusieurs mois).
5. **Defaulted** : Le compte de crédit est en défaut de paiement et est considéré comme non récupérable.
6. **Closed** : Le compte de crédit a été fermé, soit parce que la dette a été payée en totalité, soit pour une autre raison.
7. **Charge-Off** : L'émetteur du crédit considère que la dette est non recouvrable et la compte comme une perte.
8. **Settled** : Le compte a été réglé pour un montant inférieur à la totalité de la dette.
9. **Frozen** : Aucune transaction ou utilisation de crédit n'est possible sur ce compte pour le moment.
10. **Pending Approval** : Le crédit a été demandé, mais le compte n'est pas encore approuvé ou activé.
11. **Under Investigation** : Le compte est sous enquête pour des raisons liées à des litiges ou des fraudes.
12. **Restricted** : L'accès au crédit est limité ou restreint (souvent en raison de dépassements de crédit ou de comportements à risque).

## États d'une **transaction**

1. **Pending** : La transaction est en attente de traitement. Elle n'a pas encore été confirmée ou finalisée.
2. **Completed** : La transaction a été traitée avec succès et est maintenant terminée.
3. **Failed** : La transaction a échoué pour une raison quelconque, comme des fonds insuffisants ou des erreurs de traitement.
4. **Cancelled** : La transaction a été annulée avant qu'elle ne soit complétée. Cela peut être initié par l'utilisateur ou le système.
5. **Refunded** : La transaction a été annulée après avoir été complétée, et les fonds ont été retournés à l'utilisateur.
6. **Charged Back** : Une demande de remboursement a été faite sur la transaction initiale par le titulaire de la carte ou le client, souvent après une contestation.
7. **Under Review** : La transaction est en cours de vérification pour détecter d'éventuelles irrégularités ou fraudes.
8. **Settled** : La transaction a été finalisée et les fonds ont été transférés avec succès entre les parties.
9. **Rejected** : La transaction a été refusée par le système ou l'institution financière pour diverses raisons, telles que des informations incorrectes ou des problèmes de compte.
10. **Partially Refunded** : Une partie seulement du montant de la transaction initiale a été remboursée.
11. **Voided** : La transaction a été annulée avant qu'elle ne soit complètement traitée ou finalisée, souvent avant la fin de la journée de traitement.
12. **Authorized** : La transaction a été approuvée par l'institution financière, mais les fonds n'ont pas encore été transférés.
13. **Captured** : Les fonds autorisés ont été effectivement retirés du compte du client et transférés au commerçant.
14. **Reversed** : La transaction a été annulée après qu'elle ait été traitée, généralement en raison d'une erreur ou d'un problème détecté.
15. **Pending Approval** : La transaction attend l'approbation d'une partie ou d'un système avant de pouvoir être traitée ou finalisée.
