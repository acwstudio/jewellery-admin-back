<?php

declare(strict_types=1);

namespace App\Modules\Messages;

use App\Modules\Messages\Services\MailService;
use App\Packages\DataObjects\Catalog\ProductOffer\Size\NoSizeOfferData;
use App\Packages\DataObjects\Vacancies\CreateVacancyApplyData;
use App\Packages\ModuleClients\MessageModuleClientInterface;

class MessageModuleClient implements MessageModuleClientInterface
{
    public function __construct(
        private readonly MailService $mailService
    ) {
    }
    public function sendMailNoSize(
        NoSizeOfferData $noSizeOfferData
    ): void {
        $this->mailService->sendMailNoSize($noSizeOfferData);
    }

    public function applyVacancy(CreateVacancyApplyData $applyData): void
    {
        $this->mailService->sendMailApplyVacancy($applyData);
    }
}
