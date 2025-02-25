<?php

namespace Modules\Recours\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Recours\Database\factories\CommentFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Recours\App\Models\Personnal; 
use Modules\Recours\App\Models\Appeal; 


class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'message',
        'appeal_id',
        'personnal_id',
        'status',
        'last_updated_by'
    ];

    protected static function newFactory(): CommentFactory
    {
        //return CommentFactory::new();
    }
    public function appeal(): BelongsTo
    {
        return $this->belongsTo(Appeal::class, 'appeal_id');
    }

    public function personnal(): BelongsTo
    {
        return $this->belongsTo(Personnal::class, 'personnal_id');
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
