<?php

declare(strict_types=1);

use App\Http\Controllers\Questionnaire\AnswerController;
use App\Http\Controllers\Questionnaire\SurveyController;
use App\Http\Controllers\Questionnaire\QuestionController;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/questionnaire')->group(function () {
    Route::get('/survey/{uuid}', [SurveyController::class, 'get'])->whereUuid('uuid');

    Route::post('/survey/send', [SurveyController::class, 'send']);
    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/survey', [SurveyController::class, 'create']);
        Route::put('/survey/{uuid}', [SurveyController::class, 'update'])->whereUuid('uuid');

        Route::post('/question', [QuestionController::class, 'create']);
        Route::put('/question/{uuid}', [QuestionController::class, 'update'])->whereUuid('uuid');
        Route::delete('/question/{uuid}', [QuestionController::class, 'delete'])->whereUuid('uuid');
    });

    Route::post('/answer', [AnswerController::class, 'create']);
});
