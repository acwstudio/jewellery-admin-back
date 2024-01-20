<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions.promotion_benefit_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promotion_benefit_id');
            $table->string('external_id')->nullable(false);
            $table->string('sku')->nullable(false);
            $table->string('size')->nullable();
            $table->integer('price')->nullable(false);
            $table->timestamps();

            $table
                ->foreign('promotion_benefit_id')
                ->references('id')->on('promotions.promotion_benefits')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions.promotion_benefit_products');
    }
};
