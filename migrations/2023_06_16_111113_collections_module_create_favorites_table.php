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
        Schema::create('collections.favorites', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description');
            $table->char('background_color', 10)->nullable();
            $table->char('font_color', 10)->nullable();
            $table->unsignedBigInteger('collection_id');
            $table->timestamps();

            $table
                ->foreign('collection_id')
                ->references('id')->on('collections.collections')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections.favorites');
    }
};
