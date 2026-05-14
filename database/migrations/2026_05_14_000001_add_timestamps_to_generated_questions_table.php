<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generated_questions', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('concept_id');
            $table->timestamp('updated_at')->nullable()->after('generated_at');
        });
    }

    public function down(): void
    {
        Schema::table('generated_questions', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
