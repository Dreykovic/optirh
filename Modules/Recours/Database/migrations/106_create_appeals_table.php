<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['DAC', 'PROCESS', 'RESULTS', 'OTHERS'])->default('RESULTS');
            $table->date('deposit_date');
            $table->time('deposit_hour');
            $table->string('object')->nullable();
            $table->integer('day_count')->default(0);
            $table->enum('analyse_status', ['ANALYSE_PENDING', 'ACCEPTED', 'REJECTED'])->default('ANALYSE_PENDING');
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'])->default('ACTIVATED');

            $table->foreignId('dac_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('decision_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            // $table->foreignId('authority_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('last_updated_by')->nullable();
			$table->timestamps();

			$table->foreign('created_by')->references('id')
				->on('personnals')->onUpdate('cascade')->onDelete('cascade');

			$table->foreign('last_updated_by')->references('id')
				->on('personnals')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};
