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
        Schema::create('collections.files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('collections.collections', function (Blueprint $table) {
            $table->unsignedBigInteger('preview_image_id')->nullable();
            $table->unsignedBigInteger('preview_image_mob_id')->nullable();
            $table->unsignedBigInteger('banner_image_id')->nullable();
            $table->unsignedBigInteger('banner_image_mob_id')->nullable();
            $table->unsignedBigInteger('extended_image_id')->nullable();

            $table
                ->foreign('preview_image_id')
                ->references('id')->on('collections.files')->nullOnDelete();
            $table
                ->foreign('preview_image_mob_id')
                ->references('id')->on('collections.files')->nullOnDelete();
            $table
                ->foreign('banner_image_id')
                ->references('id')->on('collections.files')->nullOnDelete();
            $table
                ->foreign('banner_image_mob_id')
                ->references('id')->on('collections.files')->nullOnDelete();
            $table
                ->foreign('extended_image_id')
                ->references('id')->on('collections.files')->nullOnDelete();
        });

        Schema::table('collections.favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->nullable();
            $table->unsignedBigInteger('image_mob_id')->nullable();

            $table
                ->foreign('image_id')
                ->references('id')->on('collections.files')->nullOnDelete();
            $table
                ->foreign('image_mob_id')
                ->references('id')->on('collections.files')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('collections.favorites', [
            'image_id',
            'image_mob_id'
        ]);
        Schema::dropColumns('collections.collections', [
            'preview_image_id',
            'preview_image_mob_id',
            'banner_image_id',
            'banner_image_mob_id',
            'extended_image_id'
        ]);

        Schema::dropIfExists('collections.files');
    }
};
