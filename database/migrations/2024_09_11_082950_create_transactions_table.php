<?php

use App\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['PENDING', 'COMPLETED', 'FAILED', 'CANCELLED', 'REFUNDED', 'CHARGED_BACK', 'UNDER_REVIEW', 'SETTLED', 'REJECTED', 'PARTIALLY_REFUNDED', 'VOIDED', 'AUTHORIZED', 'CAPTURED', 'REVERSED', 'PENDING_APPROVAL'])->default('COMPLETED');
            $table->unsignedDouble('amount');
            $table->dateTime('transaction_date')->default(now());
            $table->text('description')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->on('users')->references('id')->noActionOnDelete();
            $table->unsignedBigInteger('assistant_id')->nullable();
            $table->foreign('assistant_id')->on('users')->references('id')->noActionOnDelete();

            $table->foreignIdFor(Account::class)->constrained()->noActionOnDelete();
            $table->unsignedBigInteger('transaction_type_id');
            $table->foreign('transaction_type_id')->on('transaction_types')->references('id')->noActionOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
