<?php

namespace Modules\Recours\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Recours\Database\factories\PersonnalFactory;

class Personnal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'job',
        'sexe',
    ];

    protected static function newFactory(): PersonnalFactory
    {
        // return PersonnalFactory::new();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'personnal_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Personnal::class, 'created_by');
    }

    public function updator(): BelongsTo
    {
        return $this->belongsTo(Personnal::class, 'last_updated_by');
    }
}
