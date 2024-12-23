<?php

use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('username')->nullable()->unique();

            $table->string('picture')->default('assets/images/profile_av.png');
            $table->enum('profile', ['EMPLOYEE', 'ADMIN'])->default('EMPLOYEE');
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'DELETED'])->default('ACTIVATED');

            $table->string('email');
            $table->timestamp(column: 'email_verified_at')->nullable();
            $table->string('password')->default(Hash::make('secret'));
            $table->foreignIdFor(Employee::class);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
