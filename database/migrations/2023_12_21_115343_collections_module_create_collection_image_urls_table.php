<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collections.collection_image_urls', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('collection_id');
            $table->string('path');
            $table->string('type');
            $table->timestamps();

            $table
                ->foreign('collection_id')
                ->references('id')->on('collections.collections')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections.collection_image_urls');
    }
};
