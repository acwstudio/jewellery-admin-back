<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('live.live_products', function (Blueprint $table) {
            $table->integer('number')->default(0);
        });
    }

    public function down()
    {
        Schema::dropColumns('live.live_products', 'number');
    }
};