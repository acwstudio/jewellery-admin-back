<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions.sale_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id')->unique();
            $table->timestamps();

            $table
                ->foreign('sale_id')
                ->references('id')->on('promotions.sales')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions.sale_products');
    }
};
