<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Catalog\ProductOffer\Size\NoSizeOfferData;
use App\Packages\DataObjects\Vacancies\CreateVacancyApplyData;

interface MessageModuleClientInterface
{
    public function applyVacancy(CreateVacancyApplyData $applyData): void;
    public function sendMailNoSize(NoSizeOfferData $noSizeOfferData): void;
}
