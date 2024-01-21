<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('catalog.features', function (Blueprint $table) {
            $table->integer('position')->after('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropColumns('catalog.features', 'position');
    }
};
