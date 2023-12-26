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
        Schema::create('catalog.preview_images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('catalog.products', function (Blueprint $table) {
            $table->unsignedBigInteger('preview_image_id')->nullable();

            $table
                ->foreign('preview_image_id')
                ->references('id')->on('catalog.preview_images')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('catalog.products', ['preview_image_id']);
        Schema::dropIfExists('catalog.preview_images');
    }
};
