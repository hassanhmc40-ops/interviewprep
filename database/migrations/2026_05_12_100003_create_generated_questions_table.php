<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concept_id')->constrained()->onDelete('cascade');
            $table->longText('question');
            $table->timestamp('generated_at')->useCurrent();
            $table->softDeletes();

            $table->index('concept_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_questions');
    }
};