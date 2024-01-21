<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::unprepared('CREATE SCHEMA IF NOT EXISTS catalog');
    }

    public function down()
    {
        DB::unprepared('DROP SCHEMA IF EXISTS catalog');
    }
};
