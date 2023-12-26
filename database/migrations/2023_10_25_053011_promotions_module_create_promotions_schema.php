<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('CREATE SCHEMA IF NOT EXISTS promotions');
    }

    public function down()
    {
        DB::unprepared('DROP SCHEMA IF EXISTS promotions CASCADE');
    }
};
