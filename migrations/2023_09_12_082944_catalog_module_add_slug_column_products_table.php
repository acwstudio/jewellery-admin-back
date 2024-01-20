<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('catalog.products', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::dropColumns('catalog.products', ['slug']);
    }
};
