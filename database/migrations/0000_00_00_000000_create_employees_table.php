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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Identité de l'employé
            $table->string('matricule', 20)->nullable()->unique()->comment('Identifiant unique de l\'employé');
            $table->string('first_name', 50)->comment('Prénom de l\'employé');
            $table->string('last_name', 50)->comment('Nom de famille de l\'employé');
            $table->string('email', 100)->unique()->comment('Email de l\'employé');
            $table->string('phone_number', 20)->unique()->comment('Numéro de téléphone de l\'employé');
            $table->enum('gender', ['FEMALE', 'MALE'])->default('FEMALE')->comment('le genre');

            // Adresse de l'employé
            $table->string('address1', 100)->nullable()->comment('Adresse principale');
            $table->string('address2', 100)->nullable()->comment('Complément d\'adresse');
            $table->string('city', 50)->nullable()->comment('Ville');
            $table->string('state', 50)->nullable()->comment('Code de la région ou de l\'Etat (ISO)');
            $table->string('country', 50)->nullable()->comment('Code du pays (ISO 3166-1 alpha-2)');

            // Informations bancaires
            $table->string('bank_name', 50)->nullable()->comment('Nom de la banque');
            $table->string('code_bank', 10)->nullable()->comment('Code de la banque');
            $table->string('code_guichet', 10)->nullable()->comment('Code du guichet');
            $table->string('rib', 23)->nullable()->unique()->comment('Numéro RIB complet');
            $table->string('cle_rib', 2)->nullable()->comment('Clé de vérification du RIB');
            $table->string('iban', 34)->nullable()->unique()->comment('IBAN (Numéro international de compte bancaire)');
            $table->string('swift', 11)->nullable()->comment('Code SWIFT/BIC');

            // Informations personnelles
            $table->date('birth_date')->nullable()->comment('Date de naissance');
            $table->string('nationality', 50)->nullable()->comment('Nationalité');
            $table->enum('religion', ['Christian', 'Muslim', 'Jewish', 'Hindu', 'Buddhist', 'None', 'Other'])->nullable()->comment('Religion');
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable()->comment('Statut matrimonial');
            $table->string('emergency_contact', 20)->nullable()->comment('Contact d\'urgence');

            // Statut de l'employé
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'])->default('ACTIVATED')->comment('Statut de l\'employé');

            // Horodatage
            $table->timestamps();

            // Indexation des colonnes importantes pour les performances
            $table->index(['email', 'phone_number', 'matricule']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
