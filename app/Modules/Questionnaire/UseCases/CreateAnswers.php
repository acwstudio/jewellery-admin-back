<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\UseCases;

use App\Modules\Questionnaire\Enums\SurveyTypeEnum;
use App\Modules\Questionnaire\Models\Survey;
use App\Modules\Questionnaire\Services\AnswerService;
use App\Modules\Questionnaire\Services\CompletedSurveyService;
use App\Modules\Questionnaire\Services\SurveyService;
use App\Modules\Questionnaire\Support\Blueprints\AnswerBlueprint;
use App\Modules\Questionnaire\Support\Filters\CompletedSurveyFilter;
use App\Packages\DataObjects\Questionnaire\Answer\CreateAnswerListData;
use App\Packages\Events\CompletedSurveyCreated;
use App\Packages\Exceptions\Questionnaire\SurveyFailedException;
use App\Packages\Exceptions\Questionnaire\IdentifierRequiredException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class CreateAnswers
{
    public function __construct(
        private readonly AnswerService $answerService,
        private readonly SurveyService $surveyService,
        private readonly CompletedSurveyService $completedSurveyService,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws IdentifierRequiredException
     * @throws SurveyFailedException
     */
    public function __invoke(CreateAnswerListData $answerListData): void
    {
        $survey = $this->surveyService->getSurvey($answerListData->survey_uuid);
        $identifier = $this->getIdentifier($survey, $answerListData);

        try {
            $this->prepareValidation($survey, $identifier);
            DB::transaction(function () use ($answerListData, $identifier) {
                $this->createAnswers($answerListData, $identifier);
            });
            $completedSurvey = $this->completedSurveyService->createCompletedSurvey($survey, $identifier);
        } catch (\Throwable $e) {
            $this->logger->alert("[x] Survey failed", [
                'exception' => $e, 'data' => func_get_args()
            ]);
            throw new SurveyFailedException();
        }

        CompletedSurveyCreated::dispatch(
            $completedSurvey->uuid
        );

        $this->logger->info("[+] Survey completed successfully", [
            'uuid' => $completedSurvey->uuid,
            'survey_uuid' => $completedSurvey->survey_uuid,
            'identifier' => $completedSurvey->identifier
        ]);
    }

    /**
     * @throws IdentifierRequiredException
     */
    private function getIdentifier(Survey $survey, CreateAnswerListData $answerListData): string
    {
        if (SurveyTypeEnum::EXTERNAL === $survey->type && empty($answerListData->identifier)) {
            throw new IdentifierRequiredException();
        }

        $identifiers = [
            $answerListData->identifier,
            Carbon::now()->timestamp
        ];

        return implode('|', $identifiers);
    }

    private function createAnswers(CreateAnswerListData $answerListData, string $identifier): void
    {
        /** @var \App\Packages\DataObjects\Questionnaire\Answer\CreateAnswerData $answer */
        foreach ($answerListData->answers as $answer) {
            $this->answerService->createAnswer(
                new AnswerBlueprint(
                    $identifier,
                    $answer->value,
                    $answer->comment
                ),
                $answer->question_uuid
            );
        }
    }

    private function prepareValidation(Survey $survey, string $identifier): void
    {
        $completedSurveyIsNotEmpty = $this->completedSurveyService->getCompletedSurveyByFilter(
            new CompletedSurveyFilter(survey_uuid: $survey->uuid, identifier: $identifier)
        )->isNotEmpty();

        if ($completedSurveyIsNotEmpty) {
            throw new \Exception('Данный опрос с текущим идентификатором был пройден');
        }
    }
}
