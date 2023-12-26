<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox;

use App\Packages\ApiClients\Mindbox\Contracts\MindboxApiClientContract;
use App\Packages\ApiClients\Mindbox\Enums\OperationEnum;
use App\Packages\ApiClients\Mindbox\Requests\ClientSurvey\CreateClientSurveyData;
use App\Packages\ApiClients\Mindbox\Requests\ClientSurvey\PublishedClientSurveyData;
use App\Packages\ApiClients\Mindbox\Requests\WebsiteSetWithList\CreateWebsiteSetWithListData;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;
use Spatie\LaravelData\Data;

class MindboxApiClient implements MindboxApiClientContract
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(string $url, Data $data): void
    {
        try {
            $this->logger->info("[Mindbox] Sending request", ['data' => $data->toArray()]);

            $response = Http::baseUrl(config('mindbox.url'))
                ->withToken('secretKey="' . config('mindbox.secret') . '"', 'Mindbox')
                ->withUserAgent(request()->userAgent())
                ->post($url, $data->toArray());

            if ($response->successful()) {
                $this->logger->info("[Mindbox] Success request sent");
            } else {
                $this->logger->error("[Mindbox] Request sent. Error response returned", ['response' => $response]);
            }
        } catch (\Throwable $e) {
            $this->logger->error("[Mindbox] Failed to send", ['exception' => $e]);
        }
    }

    public function clientSurvey(CreateClientSurveyData $data): void
    {
        $query = [
            'endpointId' => config('mindbox.endpoint_id'),
            'operation' => OperationEnum::CLIENT_SURVEY->value,
            'deviceUUID' => request()->header('x-device-uuid')
        ];
        $url = '?' . http_build_query($query);

        $this->send($url, $data);
    }

    public function sendClientSurvey(PublishedClientSurveyData $data): void
    {
        $query = [
            'endpointId' => config('mindbox.endpoint_id'),
            'operation' => OperationEnum::CLIENT_SURVEY->value,
            'deviceUUID' => request()->header('x-device-uuid')
        ];
        $url = '?' . http_build_query($query);

        $this->send($url, $data);
    }

    public function websiteSetWithList(CreateWebsiteSetWithListData $data): void
    {
        $query = [
            'endpointId' => config('mindbox.endpoint_id'),
            'operation' => OperationEnum::WEBSITE_SET_WISH_LIST->value,
            'deviceUUID' => request()->header('x-device-uuid')
        ];
        $url = '?' . http_build_query($query);

        $this->send($url, $data);
    }
}
