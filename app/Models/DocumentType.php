<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'description',
        'type',
    ];

    public function document_requests(): HasMany
    {
        return $this->hasMany(DocumentRequest::class, 'document_type_id');
    }
}
