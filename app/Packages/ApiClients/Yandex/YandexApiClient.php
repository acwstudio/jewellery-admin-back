<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Yandex;

use App\Packages\ApiClients\Yandex\Responses\OrderCreateResponseData;
use App\Packages\Exceptions\YandexOrderCreateException;
use Illuminate\Support\Facades\Http;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class YandexApiClient
{
    /**
     * @throws YandexOrderCreateException
     */
    public function orderCreate(
        string $externalId,
        Money $amount,
        string $onSuccessUrl,
        string $onFailUrl
    ): OrderCreateResponseData {
        $data = [
            'order_meta' => [
                'external_id' => $externalId,
                'checkout_redirect' => [
                    'on_fail' => $onFailUrl,
                    'on_success' => $onSuccessUrl
                ]
            ],
            'services' => [
                [
                    'amount' => $this->getDecimalAmount($amount),
                    'currency' => $amount->getCurrency(),
                    'type' => 'loan'
                ]
            ]
        ];

        $response = Http::yandex()->post('/b2b/order/create', $data);

        if ($response->failed()) {
            throw new YandexOrderCreateException();
        }

        return OrderCreateResponseData::from(
            $response->json()
        );
    }

    private function getDecimalAmount(Money $amount): string
    {
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        return $formatter->format($amount);
    }
}
