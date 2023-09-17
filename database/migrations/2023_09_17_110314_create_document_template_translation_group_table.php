<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('document_template_translation_group', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->uuid('document_template_id')->index();
            $table->uuid('translation_group_id')->index();

            $table->timestamps();

            $table->index([
                'document_template_id',
                'translation_group_id',
            ], 'idx_search_doc_template_translation_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_template_translation_group');
    }
};
