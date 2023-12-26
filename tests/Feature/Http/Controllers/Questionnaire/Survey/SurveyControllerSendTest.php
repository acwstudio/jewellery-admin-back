<?php

declare(strict_types=1);

namespace Http\Controllers\Questionnaire\Survey;

use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use Exception;
use Mockery\MockInterface;
use Tests\TestCase;

class SurveyControllerSendTest extends TestCase
{
    private const METHOD = '/api/v1/questionnaire/survey/send';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockMindboxApiClient();
    }

    /**
     * @throws Exception
     */
    public function testSuccessful(): void
    {
        $sendSurvey = $this->getSendSurveyData();
        $response = $this->post(self::METHOD, $sendSurvey);
        $response->assertSuccessful();
    }

    public function testFailure(): void
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
    }

    private function mockMindboxApiClient(): void
    {
        $this->mock(
            MindboxApiClientContract::class,
            function (MockInterface $mock) {
                $mock->allows('send');
                $mock->allows('sendClientSurvey');
            }
        );
    }

    /**
     * @throws Exception
     */
    private function getSendSurveyData(): array
    {
        $whatImprove = [
            "Упаковка покупки",
            "Скорость работы"
        ];
        return [
            'rateStore' => random_int(1, 5),
            'whatImprove' => $whatImprove,
            'shop' => '7777',
            'phoneNumber' => '71234567890',
            'crmId' => 'asdasdasd',
        ];
    }
}
