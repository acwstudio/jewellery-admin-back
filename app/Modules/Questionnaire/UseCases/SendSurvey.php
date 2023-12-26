<?php

declare(strict_types=1);

namespace App\Modules\Questionnaire\UseCases;

use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use App\Packages\ApiClients\Mindbox\Requests\ClientSurvey\PublishedClientSurveyData;
use App\Packages\ApiClients\Mindbox\Requests\Common\SendSurvey\CustomerData;
use App\Packages\ApiClients\Mindbox\Requests\Common\SendSurvey\CustomerActionData;
use App\Packages\ApiClients\Mindbox\Requests\Common\SendSurvey\CustomFields;
use App\Packages\DataObjects\Questionnaire\Survey\PublishedSurveyData;
use App\Packages\Events\Sync\SendSurvey1C;
use Illuminate\Support\Carbon;
use Psr\Log\LoggerInterface;

class SendSurvey
{
    public function __construct(
        private readonly MindboxApiClientContract $mindboxApiClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(PublishedSurveyData $data): void
    {
        try {
            $this->send($data);
        } catch (\Throwable $e) {
            $this->logger->alert(
                "[x] Failed publish Completed Survey #",
                ['exception' => $e]
            );
        }

        $this->logger->info("[+] Published Completed Survey #");
    }

    private function send(PublishedSurveyData $completedSurvey): void
    {
        $data = $this->getCreateClientSurveyData($completedSurvey);
        $this->mindboxApiClient->sendClientSurvey($data);

        $data1C = $this->getDataWithCrmId($data->toArray(), $completedSurvey->crmId);
        SendSurvey1C::dispatch($data1C);
    }

    private function getCreateClientSurveyData(PublishedSurveyData $completedSurvey): PublishedClientSurveyData
    {
        $data = new PublishedClientSurveyData(
            $this->getCustomerActionData($completedSurvey),
            $this->getCustomerData($completedSurvey->phoneNumber),
        );

        return $data;
    }

    private function getCustomerActionData(PublishedSurveyData $completedSurvey): CustomerActionData
    {
        return new CustomerActionData(new CustomFields(
            $completedSurvey->rateStore,
            $completedSurvey->whatImprove ?? [],
            $completedSurvey->shop
        ));
    }

    private function getCustomerData(string $identifier): CustomerData
    {
        return CustomerData::from([
            'mobilePhone' => $identifier
        ]);
    }

    private function getDataWithCrmId(array $data, string $crmId): array
    {
        $data['crmId'] = $crmId;
        return $data;
    }
}
