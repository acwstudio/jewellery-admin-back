<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questionnaire.questions', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('survey_uuid');
            $table->string('value');
            $table->jsonb('options');
            $table->timestamps();

            $table
                ->foreign('survey_uuid')
                ->references('uuid')->on('questionnaire.surveys')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questionnaire.questions');
    }
};
