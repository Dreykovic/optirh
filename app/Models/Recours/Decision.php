<?php

namespace App\Models\Recours;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Recours\Database\factories\DecisionFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Recours\Appeal;
use App\Models\Recours\Personnal;

class Decision extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'decision',
        'date',
        'status',
        'created_by',
        'last_updated_by'
    ];


    protected static function newFactory(): DecisionFactory
    {
        //return DecisionFactory::new();
    }
    public function appeals(): HasMany
    {
        return $this->hasMany(Appeal::class, 'decision_id');
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
