<?php

use App\Models\AbsenceType;
use App\Models\Duty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->string('day_requested');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('address')->nullable();
            $table->date('date_of_application');
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'DELETED'])->default('ACTIVATED');

            $table->date('date_of_approval');

            $table->string('reasons')->nullable();
            $table->string('proof')->nullable();
            $table->string('comment')->nullable();
            $table->foreignIdFor(Duty::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(AbsenceType::class)->nullable()->constrained()->onDelete('set_null');
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
