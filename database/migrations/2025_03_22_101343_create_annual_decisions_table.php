<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('annual_decisions', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('year');
            $table->string('reference')->default('/ARCOP/DG/DSAF');

            $table->date('date');
            $table->string('pdf')->nullable();
            $table->enum('state', ['current', 'outdated'])->default('current');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_decisions');
    }
};
