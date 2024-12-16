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
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nom du type de transaction
            $table->string('code')->nullable()->unique();  // Nom du type de transaction
            $table->enum('related_to', ['DEBIT', 'CREDIT']);
            $table->string('status');  // Statut du type de transaction (ex. "Actif", "Inactif")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
    }
};
