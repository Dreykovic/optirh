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
        Schema::create('duties', function (Blueprint $table) {
            $table->id();
            $table->string('duration')->nullable();

            $table->date('begin_date');
            $table->integer("absence_balance")->default(0);
            $table->string('type')->nullable();
            $table->string('comment')->nullable();
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'])->default('ACTIVATED');
            $table->enum('evolution', ['ON_GOING', 'ENDED', 'CANCEL','SUSPENDED','RESIGNED', 'DISMISSED'])->default('ON_GOING');
            $table->foreignId('job_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duties');
    }
};
