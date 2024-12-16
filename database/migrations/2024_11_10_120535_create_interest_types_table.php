<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interest_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nom du type d'intérêt (ex: Flat Rate, Fixed Rate)
            $table->text('description')->nullable();  // Description du type d'intérêt
            $table->decimal('default_rate', 5, 2)->nullable();  // Taux d'intérêt par défaut en pourcentage
            $table->boolean('is_variable')->default(false);  // Indique si le taux est variable ou fixe
            $table->enum('calculation_method', ['flat', 'reducing', 'mortgage', 'one-time'])->nullable();  // Méthode de calcul

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_types');
    }
};
