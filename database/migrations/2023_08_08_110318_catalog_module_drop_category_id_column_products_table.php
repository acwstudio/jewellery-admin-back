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
        Schema::dropColumns('catalog.products', ['category_id']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog.products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');

            $table
                ->foreign('category_id')
                ->references('id')->on('catalog.categories');
        });
    }
};
