<?php

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
        Schema::create('banner_image_banner', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('image_banner_id');
            $table->unsignedBigInteger('banner_id');
            $table->timestamps();

            $table->foreign('image_banner_id')->references('id')->on('image_banners')->cascadeOnDelete();
            $table->foreign('banner_id')->references('id')->on('banners')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_image_banner');
    }
};
