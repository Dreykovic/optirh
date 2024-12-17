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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('description')->nullable();
            $table->string('path')->nullable();
            $table->binary('data')->nullable();
            $table->date('upload_date')->nullable();
            $table->enum('status', ['ACTIVATED', 'DEACTIVATED', 'DELETED'])->default('ACTIF');

            $table->foreignIdFor(Employee::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
