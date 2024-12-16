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
        Schema::create('loan_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nom du type de prêt
            $table->string('loan_id_prefix')->nullable();  // Préfixe de l'ID du prêt
            $table->integer('starting_loan_id');  // ID de prêt de départ
            $table->decimal('minimum_amount', 15, 2);  // Montant minimum du prêt
            $table->decimal('maximum_amount', 15, 2);  // Montant maximum du prêt
            $table->decimal('interest_rate_per_year', 5, 2);  // Taux d'intérêt annuel en pourcentage
            //   $table->string('interest_type');  // Type de taux d'intérêt (ex. : fixe, variable)
            $table->integer('max_term');  // Durée maximale du prêt
            $table->string('term_period');  // Période du terme (ex. : mois, années)
            $table->decimal('late_payment_penalties', 5, 2)->nullable();  // Pénalités pour retard en pourcentage
            $table->string('status')->default('Active');  // Statut du type de prêt
            $table->decimal('loan_application_fee', 15, 2)->nullable();  // Frais de demande de prêt
            $table->string('loan_application_fee_type')->default('Fixed');  // Type de frais de demande (ex. : fixe, pourcentage)
            $table->decimal('loan_processing_fee', 15, 2)->nullable();  // Frais de traitement de prêt
            $table->string('loan_processing_fee_type')->default('Fixed');  // Type de frais de traitement (ex. : fixe, pourcentage)
            $table->text('description')->nullable();  // Description du type de prêt
            $table->unsignedBigInteger('interest_type_id');

            $table->foreign('interest_type_id')->on('interest_types')->references('id')->noActionOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_types');
    }
};
