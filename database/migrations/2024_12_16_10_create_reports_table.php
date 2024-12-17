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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('salary')->nullable();
            $table->string('file')->nullable();
            $table->string('total_labor')->nullable();
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'DELETED'])->default('ACTIF');

            $table->foreignId('duty_id')->nullable()->constrained('duties')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
