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
        Schema::table('catalog.categories', function (Blueprint $table) {
            $table->unsignedBigInteger('preview_image_id')->nullable()->after('created_at');

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
        Schema::dropColumns('catalog.categories', 'preview_image_id');
    }
};
