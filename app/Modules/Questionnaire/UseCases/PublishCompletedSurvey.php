<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\UseCases;

use App\Modules\Questionnaire\Models\CompletedSurvey;
use App\Modules\Questionnaire\Services\AnswerService;
use App\Modules\Questionnaire\Services\CompletedSurveyService;
use App\Modules\Questionnaire\Support\Filters\AnswerFilter;
use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use App\Packages\ApiClients\Mindbox\Requests\ClientSurvey\CreateClientSurveyData;
use App\Packages\ApiClients\Mindbox\Requests\Common\CustomerActionData;
use App\Packages\ApiClients\Mindbox\Requests\Common\CustomerData;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class PublishCompletedSurvey
{
    public function __construct(
        private readonly AnswerService $answerService,
        private readonly CompletedSurveyService $completedSurveyService,
        private readonly MindboxApiClientContract $mindboxApiClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(string $uuid)
    {
        try {
            $completedSurvey = $this->completedSurveyService->getCompletedSurvey($uuid);
            $this->send($completedSurvey);
        } catch (\Throwable $e) {
            $this->logger->alert(
                "[x] Failed publish Completed Survey #$uuid",
                ['exception' => $e]
            );
        }

        $this->logger->info("[+] Published Completed Survey #$uuid");
    }

    private function send(CompletedSurvey $completedSurvey): void
    {
        $data = $this->getCreateClientSurveyData($completedSurvey);
        $this->mindboxApiClient->clientSurvey($data);
    }

    private function getCreateClientSurveyData(CompletedSurvey $completedSurvey): CreateClientSurveyData
    {
        $answers = $this->answerService->getAnswers(
            new AnswerFilter($completedSurvey->survey_uuid, $completedSurvey->identifier)
        );

        return new CreateClientSurveyData(
            $this->getCustomerActionData($answers),
            $this->getCustomerData($completedSurvey->identifier),
            $completedSurvey->created_at->utc()->format('Y-m-d h:i:s')
        );
    }

    private function getCustomerActionData(Collection $answers): CustomerActionData
    {
        $customFields = [];
        /** @var \App\Modules\Questionnaire\Models\Answer $answer */
        foreach ($answers as $key => $answer) {
            $customFields[$answer->question->code ?? $answer->question->uuid] = $answer->value;
            if (!empty($answer->comment)) {
                $commentKey = 'comment' . (intval($key) + 1);
                $customFields[$commentKey] = $answer->comment;
            }
        }

        return new CustomerActionData(collect($customFields));
    }

    private function getCustomerData(string $identifier): CustomerData
    {
        $identifiers = explode('|', $identifier);

        return CustomerData::from([
            'mobilePhone' => $identifiers[0] ?? null
        ]);
    }
}
