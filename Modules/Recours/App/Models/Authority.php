<?php

namespace Modules\Recours\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Recours\Database\factories\AuthorityFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Recours\App\Models\Personnal; 
use Modules\Recours\App\Models\Appeal; 

class Authority extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'last_updated_by'
    ];

    
    protected static function newFactory(): AuthorityFactory
    {
        //return AuthorityFactory::new();
    }
    public function appeals(): HasMany
    {
        return $this->hasMany(Appeal::class, 'authority_id');
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
