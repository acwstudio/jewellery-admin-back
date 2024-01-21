<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('promotions.promotion_benefits', function (Blueprint $table) {
            $table->string('type_form')->nullable();
        });
    }

    public function down()
    {
        Schema::dropColumns('promotions.promotion_benefits', ['type_form']);
    }
};
