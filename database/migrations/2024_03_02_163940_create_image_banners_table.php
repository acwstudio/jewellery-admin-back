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
        Schema::create('image_banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_device_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('slug');
            $table->boolean('is_active');
            $table->string('extension')->nullable();
            $table->string('size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('model_type')->nullable();
            $table->string('image_link')->nullable();
            $table->string('content_link')->nullable();
            $table->integer('sequence')->nullable();
            $table->timestamps();

            $table->foreign('type_device_id')->references('id')->on('type_devices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_banners');
    }
};
