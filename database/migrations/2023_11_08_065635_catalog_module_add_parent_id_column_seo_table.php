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
            $table->bigInteger('parent_id')->nullable();

            $table
                ->foreign('parent_id')
                ->references('id')->on('catalog.seo')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropColumns('catalog.seo', ['parent_id']);
    }
};
