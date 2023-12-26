<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('catalog.product_offers', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'size']);
            $table->unique(['product_id', 'size', 'weight']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog.product_offers', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'size', 'weight']);
            $table->unique(['product_id', 'size']);
        });
    }
};
