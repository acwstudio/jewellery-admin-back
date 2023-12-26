<?php

declare(strict_types=1);

namespace App\Modules\Messages\Services;

use App\Modules\Messages\Mails\NoSize;
use App\Modules\Vacancies\Mail\VacancyApplicationMail;
use App\Packages\DataObjects\Catalog\ProductOffer\Size\NoSizeOfferData;
use App\Packages\DataObjects\Vacancies\CreateVacancyApplyData;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendMailNoSize(
        NoSizeOfferData $noSizeOfferData
    ): void {
        Mail::to(config('messages.mails.no_size'))->send(new NoSize($noSizeOfferData));
    }

    public function sendMailApplyVacancy(
        CreateVacancyApplyData $applyData
    ): void {
        Mail::to(config('app.hr_email'))->send(new VacancyApplicationMail($applyData));
    }
}
