<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::table('languages')
            ->insertOrIgnore([
                'code' => 'en',
                'name' => 'English',
            ]);
    }

    public function down(): void
    {
        DB::table('languages')
            ->where('code', 'en')
            ->delete();
    }
};
