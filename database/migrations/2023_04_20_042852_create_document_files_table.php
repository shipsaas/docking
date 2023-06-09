<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_files', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('document_template_uuid')->index();

            $table->string('path')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('url')->nullable();

            $table->jsonb('variables')->nullable();
            $table->jsonb('metadata')->nullable();

            $table->boolean('is_preview_file')->default(false)
                ->index();

            $table->timestamps();
        });
    }
};
