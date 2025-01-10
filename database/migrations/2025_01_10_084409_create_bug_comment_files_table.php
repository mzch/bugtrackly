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
        Schema::create('bug_comment_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bug_comment_id')->constrained()->onDelete('cascade'); // Clé étrangère
            $table->string('file_path'); // Chemin du fichier
            $table->bigInteger('size')->unsigned(); // Chemin du fichier
            $table->string('size_human_readable'); // Chemin du fichier
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_comment_files');
    }
};
