<?php

namespace App\Config;

class ActivityLogActions
{
    /**
     * Types d'actions disponibles pour la journalisation
     * Structure:
     * 'code' => [
     *    'display' => 'Libellé d'affichage',
     *    'icon' => 'Class CSS d'icône',
     *    'color' => 'Class de couleur ou code couleur',
     *    'group' => 'Groupe pour le filtrage'
     * ]
     */
    public const ACTIONS = [
        // Actions de visualisation
        'view' => [
            'display' => 'Consultation',
            'icon' => 'fa-eye',
            'color' => 'text-info',
            'group' => 'view',
        ],
        'access' => [
            'display' => 'Accès',
            'icon' => 'fa-door-open',
            'color' => 'text-info',
            'group' => 'view',
        ],
        'download' => [
            'display' => 'Téléchargement',
            'icon' => 'fa-download',
            'color' => 'text-info',
            'group' => 'view',
        ],

        // Actions de création/modification
        'created' => [
            'display' => 'Création',
            'icon' => 'fa-plus-circle',
            'color' => 'text-success',
            'group' => 'write',
        ],
        'updated' => [
            'display' => 'Modification',
            'icon' => 'fa-edit',
            'color' => 'text-primary',
            'group' => 'write',
        ],
        'uploaded' => [
            'display' => 'Téléversement',
            'icon' => 'fa-upload',
            'color' => 'text-primary',
            'group' => 'write',
        ],
        'commented' => [
            'display' => 'Commentaire',
            'icon' => 'fa-comment',
            'color' => 'text-primary',
            'group' => 'write',
        ],

        // Actions d'approbation/rejet
        'approved' => [
            'display' => 'Approbation',
            'icon' => 'fa-check-circle',
            'color' => 'text-success',
            'group' => 'decision',
        ],
        'rejected' => [
            'display' => 'Rejet',
            'icon' => 'fa-times-circle',
            'color' => 'text-danger',
            'group' => 'decision',
        ],
        'cancelled' => [
            'display' => 'Annulation',
            'icon' => 'fa-ban',
            'color' => 'text-secondary',
            'group' => 'decision',
        ],

        // Actions de suppression
        'deleted' => [
            'display' => 'Suppression',
            'icon' => 'fa-trash-alt',
            'color' => 'text-danger',
            'group' => 'delete',
        ],

        // Actions de sécurité/erreurs
        'login' => [
            'display' => 'Connexion',
            'icon' => 'fa-sign-in-alt',
            'color' => 'text-primary',
            'group' => 'security',
        ],
        'logout' => [
            'display' => 'Déconnexion',
            'icon' => 'fa-sign-out-alt',
            'color' => 'text-secondary',
            'group' => 'security',
        ],
        'denied' => [
            'display' => 'Accès refusé',
            'icon' => 'fa-user-lock',
            'color' => 'text-danger',
            'group' => 'security',
        ],
        'error' => [
            'display' => 'Erreur',
            'icon' => 'fa-exclamation-triangle',
            'color' => 'text-danger',
            'group' => 'error',
        ],
        'info' => [
            'display' => 'Information',
            'icon' => 'fa-info-circle',
            'color' => 'text-secondary',
            'group' => 'view',
        ],
        'warning' => [
            'display' => 'Avertissement',
            'icon' => 'fa-exclamation-circle',
            'color' => 'text-warning',
            'group' => 'error',
        ],
    ];

    /**
     * Groupes d'actions pour le filtrage
     */
    public const ACTION_GROUPS = [
        'all' => [
            'display' => 'Toutes les actions',
            'icon' => 'fa-list',
            'color' => 'text-dark',
        ],
        'view' => [
            'display' => 'Consultations',
            'icon' => 'fa-eye',
            'color' => 'text-info',
        ],
        'write' => [
            'display' => 'Modifications',
            'icon' => 'fa-edit',
            'color' => 'text-primary',
        ],
        'decision' => [
            'display' => 'Décisions',
            'icon' => 'fa-check',
            'color' => 'text-success',
        ],
        'delete' => [
            'display' => 'Suppressions',
            'icon' => 'fa-trash-alt',
            'color' => 'text-danger',
        ],
        'security' => [
            'display' => 'Sécurité',
            'icon' => 'fa-shield-alt',
            'color' => 'text-warning',
        ],
        'error' => [
            'display' => 'Erreurs',
            'icon' => 'fa-exclamation-triangle',
            'color' => 'text-danger',
        ],
    ];

    /**
     * Récupère les informations d'une action par son code
     *
     * @param  string  $actionCode
     * @return array|null
     */
    public static function getAction($actionCode)
    {
        return self::ACTIONS[$actionCode] ?? null;
    }

    /**
     * Récupère le libellé d'affichage d'une action
     *
     * @param  string  $actionCode
     * @return string
     */
    public static function getActionDisplay($actionCode)
    {
        return self::ACTIONS[$actionCode]['display'] ?? $actionCode;
    }

    /**
     * Récupère l'icône d'une action
     *
     * @param  string  $actionCode
     * @return string
     */
    public static function getActionIcon($actionCode)
    {
        return self::ACTIONS[$actionCode]['icon'] ?? 'fa-question';
    }

    /**
     * Récupère la couleur d'une action
     *
     * @param  string  $actionCode
     * @return string
     */
    public static function getActionColor($actionCode)
    {
        return self::ACTIONS[$actionCode]['color'] ?? 'text-secondary';
    }

    /**
     * Récupère le groupe d'une action
     *
     * @param  string  $actionCode
     * @return string
     */
    public static function getActionGroup($actionCode)
    {
        return self::ACTIONS[$actionCode]['group'] ?? 'all';
    }

    /**
     * Récupère tous les codes d'actions
     *
     * @return array
     */
    public static function getAllActionCodes()
    {
        return array_keys(self::ACTIONS);
    }

    /**
     * Récupère tous les codes d'actions pour un groupe donné
     *
     * @param  string  $groupCode
     * @return array
     */
    public static function getActionCodesByGroup($groupCode)
    {
        if ($groupCode === 'all') {
            return self::getAllActionCodes();
        }

        return array_keys(array_filter(self::ACTIONS, function ($action) use ($groupCode) {
            return $action['group'] === $groupCode;
        }));
    }

    /**
     * Récupère les informations d'un groupe par son code
     *
     * @param  string  $groupCode
     * @return array|null
     */
    public static function getGroup($groupCode)
    {
        return self::ACTION_GROUPS[$groupCode] ?? null;
    }

    /**
     * Récupère tous les groupes d'actions
     *
     * @return array
     */
    public static function getAllGroups()
    {
        return self::ACTION_GROUPS;
    }

    /**
     * Noms d'affichage des modèles pour une meilleure lisibilité
     */
    public const MODEL_DISPLAY_NAMES = [
        'App\\Models\\User' => 'Utilisateur',
        'App\\Models\\OptiHr\\Employee' => 'Employé',
        'App\\Models\\OptiHr\\Absence' => 'Absence',
        'App\\Models\\OptiHr\\Duty' => 'Affectation',
        'App\\Models\\OptiHr\\Job' => 'Poste',
        'App\\Models\\OptiHr\\Department' => 'Département',
        'App\\Models\\OptiHr\\DocumentRequest' => 'Demande de document',
        'App\\Models\\OptiHr\\Publication' => 'Publication',
        'App\\Models\\OptiHr\\AnnualDecision' => 'Décision annuelle',
        'App\\Models\\Recours\\Appeal' => 'Recours',
        'App\\Models\\Recours\\Applicant' => 'Requérant',
        'App\\Models\\ActivityLog' => 'Journal d\'activité',
    ];

    /**
     * Récupère le nom d'affichage d'un modèle
     *
     * @param  string|null  $modelType
     * @return string
     */
    public static function getModelDisplayName($modelType)
    {
        if (! $modelType) {
            return '';
        }

        return self::MODEL_DISPLAY_NAMES[$modelType] ?? class_basename($modelType);
    }
}
