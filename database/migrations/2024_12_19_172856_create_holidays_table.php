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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('country')->default('Togo');
            $table->boolean('is_public_holiday')->default(true);
            $table->boolean('is_religious')->default(false);
            $table->string('religion')->nullable();
            $table->boolean('is_fixed')->default(true);
            $table->string('day_of_week')->nullable();
            $table->string('recurrence_rule')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
