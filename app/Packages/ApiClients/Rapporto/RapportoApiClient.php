<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Rapporto;

use App\Packages\ApiClients\Rapporto\Requests\RapportoSendData;
use App\Packages\Enums\Rapporto\RapportoMessageTypeEnum;
use App\Packages\Support\PhoneNumber;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;
use Throwable;

class RapportoApiClient
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly string $login,
        private readonly string $password,
        private readonly string $uri,
        private readonly string $serviceNumber,
    ) {
    }

    public function send(
        PhoneNumber $phone,
        string $message,
        RapportoMessageTypeEnum $type
    ): void {
        $id = $this->createId();

        try {
            $this->logger->info("[rapporto] Sending message with ID:$id", ['phone' => $phone, 'message' => $message]);
            $data = $this->prepareData($phone, $message, $id, $type)->toArray();

            $response = Http::rapporto()->post($this->uri, $data);

            if ($response->ok()) {
                $this->logger->info("[rapporto] Success message sent with ID:$id");
            } else {
                $this->logger->error("[rapporto] Message sent. Error response returned", ['response' => $response]);
            }
        } catch (Throwable $e) {
            $this->logger->error(
                "[rapporto] Failed to send message with ID:$id",
                ['exception' => $e]
            );
        }
    }

    private function prepareData(PhoneNumber $phone, string $message, string $id): RapportoSendData
    {
        return new RapportoSendData(
            $this->login,
            $this->password,
            $id,
            $phone,
            $message,
            $this->serviceNumber
        );
    }

    private function createId(): string
    {
        return 'uvi' . md5(random_bytes(32));
    }
}
