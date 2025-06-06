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
        Schema::create('absence_types', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('is_deductible')->default(true); // Nouvel attribut pour indiquer si l'absence est déductible du solde de congés

            $table->enum('type', ['EXCEPTIONAL', 'NORMAL'])->default('NORMAL');
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'])->default('ACTIVATED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absence_types');
    }
};
