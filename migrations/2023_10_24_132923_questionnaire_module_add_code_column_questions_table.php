<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questionnaire.questions', function (Blueprint $table) {
            $table->string('code')->nullable();
        });
    }

    public function down()
    {
        Schema::dropColumns('questionnaire.questions', ['code']);
    }
};
