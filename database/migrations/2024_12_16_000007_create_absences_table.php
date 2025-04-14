<?php

use App\Models\OptiHr\AbsenceType;
use App\Models\OptiHr\Duty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('requested_days'); // Nombre total de jours demandés
            $table->enum('level', ['ZERO', 'ONE', 'TWO', 'THREE', 'FOUR'])->default('ZERO'); // Priorité de l'absence
            $table->date('start_date');
            $table->date('end_date');
            $table->string('address')->nullable();
            $table->dateTime('date_of_application')->default(now());
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'])->default('ACTIVATED');

            $table->date('date_of_approval')->nullable();
            $table->enum('stage', ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED'])->default('PENDING');

            $table->text('reasons')->nullable();
            $table->string('proof')->nullable();
            $table->text('comment')->nullable();
            $table->bigInteger('absence_number')->nullable();
            $table->boolean('is_deductible')->default(true); // Nouvel attribut pour indiquer si l'absence est déductible du solde de congés

            $table->foreignIdFor(Duty::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(AbsenceType::class)->nullable()->constrained()->onDelete('set null'); // Clé étrangère vers le type d'absence

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
