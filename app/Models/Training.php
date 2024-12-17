<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Training extends Model
{
    use HasFactory;
    /*
     *  $table->string('title');
     * $table->text('description')->nullable();
     * $table->text('content')->nullable();
     * $table->string('problematic')->nullable();
     * $table->string('skills_to_acquire')->nullable();
     * $table->string('training_label')->nullable();
     * $table->string('indicators_of_success')->nullable();
     * $table->string('execution_method')->nullable();
     * $table->string('implementation_period')->nullable();
     * $table->string('second_implementation_period')->nullable();
     * $table->string('superior_observation')->nullable();
     * $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'DELETED'])->default('ACTIVATED');
     *
     * $table->foreignIdFor(Duty::class)->nullable();
     */
    protected $fillable = [
        'title',
        'description',
        'content',
        'problematic',
        'skills_to_acquire',
        'indicators_of_success',
        'execution_method',
        'implementation_period',
        'second_implementation_period',
        'superior_observation',
        'status',
        'duty_id',
    ];

    public function duty(): BelongsTo
    {
        return $this->belongsTo(Duty::class, 'duty_id');
    }
}
