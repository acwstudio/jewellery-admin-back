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
        Schema::create('collections.collection_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id');
            $table->unsignedBigInteger('image_id');
            $table->integer('order_column')->default(1);

            $table
                ->foreign('collection_id')
                ->references('id')->on('collections.collections')->cascadeOnDelete();

            $table
                ->foreign('image_id')
                ->references('id')->on('collections.files')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections.collection_images');
    }
};
