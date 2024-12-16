<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_name')->nullable();
            $table->string('client_no');
            $table->string('zip')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender');
            $table->string('country')->nullable();
            $table->string('city');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('state')->nullable();
            $table->string('phone');
            $table->string('job')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['ACTIF', 'INACTIF', 'DELETED'])->default('ACTIF');
            $table->string('profile_picture')->nullable();
            $table->foreignIdFor(User::class)->noActionOnDelete()->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
