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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();

            $table->decimal('interest_rate', 5, 2)->default(0.0);

            $table->string('name');  // Nom du type de compte
            $table->unsignedBigInteger('next_account_number')->default(1000);

            $table->string('account_number_prefix')->nullable();  // Préfixe du numéro de compte
            $table->integer('starting_account_number');  // Numéro de compte de départ
            $table->string('currency');  // Devise (par exemple, USD, EUR)
            $table->string('interest_period');  // Période d'intérêt (par exemple, "mensuel", "annuel")
            $table->string('interest_method');  // Méthode de calcul des intérêts (par exemple, "Daily Outstanding Balance")
            $table->decimal('minimum_balance_for_interest', 15, 2)->default(0);  // Solde minimum pour générer des intérêts
            $table->boolean('allow_withdraw')->default(true);  // Permet les retraits (par défaut, oui)
            $table->decimal('minimum_deposit_amount', 15, 2)->default(0);  // Montant minimum du dépôt
            $table->decimal('minimum_account_balance', 15, 2)->default(0);  // Solde minimum du compte
            $table->decimal('maintenance_fee', 15, 2)->default(0);  // Frais de maintenance
            $table->string('maintenance_fee_deduct_method')->default('Select One');  // Méthode de déduction des frais de maintenance
            $table->string('status')->default('ACTIVE');  // Statut du type de compte (par défaut, "Active")
            $table->string('maintenance_fee_posting_month')->nullable();  // Mois de publication des frais de maintenance (par exemple, "June")

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
