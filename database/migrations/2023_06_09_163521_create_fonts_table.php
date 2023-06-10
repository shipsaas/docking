<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fonts', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('path');
            $table->timestamps();
        });
    }
};
