<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions.sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promotion_id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->timestamps();

            $table
                ->foreign('promotion_id')
                ->references('id')->on('promotions.promotions')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions.sales');
    }
};
