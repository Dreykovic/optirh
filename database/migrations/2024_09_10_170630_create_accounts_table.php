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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_no')->unique();

            $table->enum('state', ['ACTIVE', 'INACTIVE', 'SUSPENDED', 'CLOSED', 'PENDING_ACTIVATION', 'PENDING_CLOSURE', 'UNDER_REVIEW', 'BLOCKED', 'FROZEN', 'TERMINATED'])->default('ACTIVE');
            $table->unsignedDouble('current_balance')->default(0);
            $table->unsignedBigInteger('loan_guarantee_amount');
            $table->unsignedDouble('fees')->default(0);
            $table->dateTime('creation_date')->default(now());
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('assistant_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('account_type_id');
            $table->foreign('employee_id')->on('users')->references('id')->noActionOnDelete();
            $table->foreign('account_type_id')->on('account_types')->references('id')->noActionOnDelete();
            $table->foreign('client_id')->on('clients')->references('id')->noActionOnDelete();

            $table->foreign('assistant_id')->on('users')->references('id')->noActionOnDelete()->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
