<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('catalog.seo', function (Blueprint $table) {
            $table->dropForeign('catalog_seo_parent_id_foreign');

            $table
                ->foreign('parent_id')
                ->references('id')->on('catalog.seo')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('catalog.seo', function (Blueprint $table) {
            $table->dropForeign('catalog_seo_parent_id_foreign');

            $table
                ->foreign('parent_id')
                ->references('id')->on('catalog.seo')
                ->nullOnDelete();
        });
    }
};
