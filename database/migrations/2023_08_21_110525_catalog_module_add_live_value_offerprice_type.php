<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TYPE catalog.offerPrice ADD VALUE IF NOT EXISTS 'live'");
    }

    public function down()
    {
    }
};
