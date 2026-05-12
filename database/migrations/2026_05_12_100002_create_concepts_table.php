<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->longText('explanation');
            $table->enum('difficulty_level', ['junior', 'mid', 'senior'])->default('junior');
            $table->enum('status', ['to_review', 'in_progress', 'mastered'])->default('to_review');
            $table->timestamps();
            $table->softDeletes();

            $table->index('domain_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concepts');
    }
};