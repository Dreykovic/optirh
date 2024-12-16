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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_id')->unique();
            $table->string('status');
            $table->date('first_payment_date');
            $table->date('release_date');
            $table->decimal('applied_amount', 15, 2);
            $table->decimal('total_principal_paid', 15, 2)->default(0);
            $table->decimal('total_interest_paid', 15, 2)->default(0);
            $table->decimal('total_penalties_paid', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2);
            $table->string('purpose_of_loan')->nullable();
            $table->string('attachment')->nullable();
            $table->date('approved_date');
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('approver_id');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('fees_deduct_account_id');
            $table->unsignedBigInteger(column: 'loan_type_id');
            $table->foreign('fees_deduct_account_id')->on('accounts')->references('id')->noActionOnDelete();
            $table->foreign('creator_id')->on('users')->references('id')->noActionOnDelete();
            $table->foreign('client_id')->on('clients')->references('id')->noActionOnDelete();
            $table->foreign('loan_type_id')->on('loan_types')->references('id')->noActionOnDelete();
            $table->foreign('approver_id')->on('users')->references('id')->noActionOnDelete()->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
