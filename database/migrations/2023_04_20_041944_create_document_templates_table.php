<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('key')->index();
            $table->string('category')->default('Default');
            $table->string('title');
            $table->longText('template')->nullable();
            $table->jsonb('default_variables')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('deleted_at');
            $table->unique(['key', 'deleted_at']);

            $table->comment('Holds the document templates');
        });
    }
};
