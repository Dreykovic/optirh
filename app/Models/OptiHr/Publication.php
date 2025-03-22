<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publication extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'content',
        'author_id',
        'published_at',
        'status',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all of the files for the Publication.
     */
    public function files(): HasMany
    {
        return $this->hasMany(PublicationFile::class, 'publication_id');
    }
}
