<?php

use App\Models\Duty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('problematic')->nullable();
            $table->string('skills_to_acquire')->nullable();
            $table->string('training_label')->nullable();
            $table->string('indicators_of_success')->nullable();
            $table->string('execution_method')->nullable();
            $table->string('implementation_period')->nullable();
            $table->string('second_implementation_period')->nullable();
            $table->string('superior_observation')->nullable();
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'])->default('ACTIVATED');

            $table->foreignIdFor(Duty::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
