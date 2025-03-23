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
        Schema::create('dacs', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('object')->nullable();
            $table->string('ac')->nullable();
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'])->default('ACTIVATED');

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
        Schema::dropIfExists('dacs');
    }
};
