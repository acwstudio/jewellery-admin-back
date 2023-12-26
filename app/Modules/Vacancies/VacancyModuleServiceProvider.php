<?php

declare(strict_types=1);

namespace App\Modules\Vacancies;

use App\Packages\ModuleClients\VacancyModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class VacancyModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        VacancyModuleClientInterface::class => VacancyModuleClient::class,
    ];
}
