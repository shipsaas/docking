<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        DB::table('languages')
            ->insertOrIgnore([
                'uuid' => Str::orderedUuid()->toString(),
                'code' => 'en',
                'name' => 'English',
            ]);
    }

    public function down(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        DB::table('languages')
            ->where('code', 'en')
            ->delete();
    }
};
