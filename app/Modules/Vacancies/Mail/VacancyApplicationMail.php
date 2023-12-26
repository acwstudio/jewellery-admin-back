<?php

declare(strict_types=1);

namespace App\Modules\Vacancies\Mail;

use App\Packages\DataObjects\Vacancies\CreateVacancyApplyData;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VacancyApplicationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly CreateVacancyApplyData $applicationData
    ) {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('Новая заявка на вакансию')
            ->view('emails.vacancy-application')
            ->with(['applicationData' => $this->applicationData]);

        if ($this->applicationData->resume instanceof UploadedFile) {
            $mail->attach(
                $this->applicationData->resume->getRealPath(),
                [
                    'as' => $this->applicationData->resume->getClientOriginalName(),
                    'mime' => $this->applicationData->resume->getClientMimeType(),
                ]
            );
        }

        return $mail;
    }
}
