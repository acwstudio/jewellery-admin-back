<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\CompletedSurveyCreated;
use App\Packages\ModuleClients\QuestionnaireModuleClientInterface;

class PublishCompletedSurvey
{
    public function __construct(
        private readonly QuestionnaireModuleClientInterface $questionnaireModuleClient
    ) {
    }

    public function handle(CompletedSurveyCreated $event): void
    {
        $this->questionnaireModuleClient->publishCompletedSurvey($event->uuid);
    }
}
