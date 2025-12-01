<?php

namespace App\Models;

use App\Config\ActivityLogActions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'additional_data',
    ];

    /**
     * Les attributs qui doivent être typés.
     *
     * @var array
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'additional_data' => 'array',
    ];

    /**
     * Relation avec l'utilisateur qui a effectué l'action
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation polymorphique avec le modèle concerné par l'action
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo('subject', 'model_type', 'model_id');
    }

    /**
     * Scope pour filtrer par type d'action
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $action
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par groupe d'actions
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $group
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActionGroup($query, $group)
    {
        if ($group === 'all') {
            return $query;
        }

        $actionCodes = ActivityLogActions::getActionCodesByGroup($group);

        return $query->whereIn('action', $actionCodes);
    }

    /**
     * Scope pour filtrer par utilisateur
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour filtrer par plage de dates
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $from
     * @param  string  $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $from, $to)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    /**
     * Scope pour filtrer par type de modèle
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $modelType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Obtenir le libellé d'affichage de l'action
     *
     * @return string
     */
    public function getActionDisplayAttribute()
    {
        return ActivityLogActions::getActionDisplay($this->action);
    }

    /**
     * Obtenir l'icône de l'action
     *
     * @return string
     */
    public function getActionIconAttribute()
    {
        return ActivityLogActions::getActionIcon($this->action);
    }

    /**
     * Obtenir la couleur de l'action
     *
     * @return string
     */
    public function getActionColorAttribute()
    {
        return ActivityLogActions::getActionColor($this->action);
    }

    /**
     * Obtenir le groupe de l'action
     *
     * @return string
     */
    public function getActionGroupAttribute()
    {
        return ActivityLogActions::getActionGroup($this->action);
    }

    /**
     * Obtenir le nom lisible du type de modèle
     *
     * @return string|null
     */
    public function getModelTypeDisplayAttribute()
    {
        if (! $this->model_type) {
            return null;
        }

        return ActivityLogActions::getModelDisplayName($this->model_type);
    }
}
