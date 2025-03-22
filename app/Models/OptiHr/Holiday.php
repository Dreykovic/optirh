<?php

namespace App\Models\OptiHr;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'name',
        'date',
    ];
}
