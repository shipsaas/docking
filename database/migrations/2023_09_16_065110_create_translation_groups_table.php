<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('translation_groups', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->string('key')->unique();
            $table->string('name');
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_groups');
    }
};
