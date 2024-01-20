<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared('CREATE SCHEMA IF NOT EXISTS questionnaire');
    }

    public function down()
    {
        DB::unprepared('DROP SCHEMA IF EXISTS questionnaire CASCADE');
    }
};
