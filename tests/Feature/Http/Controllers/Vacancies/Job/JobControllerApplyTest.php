<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Vacancies\Job;

use App\Modules\Vacancies\Mail\VacancyApplicationMail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class JobControllerApplyTest extends TestCase
{
    private const METHOD = '/api/v1/vacancies/vacancy/apply';

    public function testSuccessful()
    {
        Mail::fake();

        $file = UploadedFile::fake()->create('resume.pdf', 1024); // Создание фиктивного файла резюме

        $data = [
            'city' => 'Москва',
            'department' => 'IT',
            'job' => 'Разработчик',
            'surname' => 'Иванов',
            'name' => 'Алексей',
            'citizenship' => 'Россия',
            'phone' => '+7 123-456-7890',
            'email' => 'example@example.com',
            'resume' => $file,
        ];

        $response = $this->postJson(self::METHOD, $data);
        $response->assertSuccessful();

        Mail::assertSent(
            VacancyApplicationMail::class,
            function ($mail) {
                $mailData = $mail->build();

                $this->assertCount(1, $mailData->attachments);
                $attachment = $mailData->attachments[0];
                $this->assertEquals('resume.pdf', $attachment['options']['as']);
                $this->assertEquals('application/pdf', $attachment['options']['mime']);

                return true;
            }
        );
    }
}
