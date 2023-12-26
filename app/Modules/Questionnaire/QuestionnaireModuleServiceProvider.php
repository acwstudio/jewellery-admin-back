<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire;

use App\Packages\ModuleClients\QuestionnaireModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class QuestionnaireModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        QuestionnaireModuleClientInterface::class => QuestionnaireModuleClient::class,
    ];
}
