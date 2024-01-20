<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::unprepared('CREATE SCHEMA IF NOT EXISTS payments');
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::unprepared('DROP SCHEMA IF EXISTS payments');
    }
};
