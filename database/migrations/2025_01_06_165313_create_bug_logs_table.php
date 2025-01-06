<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bug_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bug_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable()->constrained()->onDelete('set null'); // Utilisateur ayant effectué l'action
            $table->string('action');
            $table->string('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_logs');
    }
};
