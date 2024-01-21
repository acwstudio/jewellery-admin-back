<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('ALTER TABLE delivery.currier_delivery_addresses ALTER COLUMN zip_code TYPE integer USING (zip_code::integer)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery.currisader_delivery_addresses', function (Blueprint $table) {
            $table->string('zip_code')->change();
        });
    }
};
