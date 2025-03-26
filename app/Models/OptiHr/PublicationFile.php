<?php

namespace App\Models\OptiHr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',
        'mime_type',
        'description',
        'path',
        'data',
        'upload_date',
        'status',
        'name',
        'display_name',
        'publication_id',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }
}
