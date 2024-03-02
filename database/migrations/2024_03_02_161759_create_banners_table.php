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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_banner_id');
            $table->unsignedBigInteger('type_page_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug');
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('type_banner_id')->references('id')->on('type_banners');
            $table->foreign('type_page_id')->references('id')->on('type_pages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
};
