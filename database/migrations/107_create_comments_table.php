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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->foreignId('appeal_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('personnal_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'])->default('ACTIVATED');

			$table->unsignedBigInteger('last_updated_by')->nullable();
			$table->timestamps();

			$table->foreign('last_updated_by')->references('id')
				->on('personnals')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
