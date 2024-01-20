<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('catalog.product_video_urls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('path');
            $table->timestamps();

            $table
                ->foreign('product_id')
                ->references('id')->on('catalog.products')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('catalog.product_video_urls');
    }
};
