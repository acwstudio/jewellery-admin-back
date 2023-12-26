<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('collections.collections', static function (Blueprint $table) {
            $table->string('external_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('collections.collections', static function (Blueprint $table) {
            $table->dropColumn(['external_id']);
        });
    }
};
