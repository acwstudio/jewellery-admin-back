<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE catalog.products ALTER COLUMN description_1c TYPE TEXT');
    }

    public function down()
    {
        DB::statement('ALTER TABLE catalog.products ALTER COLUMN description_1c TYPE VARCHAR(255)');
    }
};