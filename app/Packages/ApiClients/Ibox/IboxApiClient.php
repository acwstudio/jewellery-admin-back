<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Ibox;

use App\Packages\ApiClients\Ibox\Responses\PaymentSubmitResponseData;
use App\Packages\DataObjects\Orders\Product\OrderProductData;
use App\Packages\Enums\IboxInputTypeEnum;
use App\Packages\Exceptions\FiscalizationPaymentSubmitException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class IboxApiClient
{
    protected const TAX_CODE = 'VAT2000';

    public function paymentSubmit(
        string $orderNumber1c,
        Money $amount,
        string $uuid,
        IboxInputTypeEnum $inputType,
        Collection $products,
        ?string $extId = null
    ): PaymentSubmitResponseData {
        $body = [
            'Receipt' => null,
            'Location' => [
                'Latitude' => 55.803127491787,
                'Longitude' => 37.620191815254
            ],
            'Country' => 'Russia',
            'CountryCode' => 'RU',
            'DeviceInfo' => [
                'PhoneManufacturer' => 'ТЕЛЕРИТЕЙЛ',
                'PhoneModel' => 'Web_Fiscalizer_Payment_Service',
                'DeviceID' => 'none',
                'DeviceType' => '1',
                'AppFramework' => 'javascript',
                'OS' => 'debian',
                'OSVersion' => '20.04.1',
                'AppID' => 'Web_Fiscalizer_Worker',
                'BuildNumber' => '1.000'
            ],
            'GMT' => 3,
            'AppFramework' => 'javascript',
            'IP' => '92.246.150.0',
            'Lang' => 'ru',
            'Card' => null,
            'AcqTran' => [
                'AcquirerCode' => 'OUTERCARD',
                'SingleStepAuthMode' => true,
            ],
            'BasicTran' => [
                'InputType' => $inputType,
                'CurrencyID' => 'RUB',
                'ServiceID' => 'CARDPORT-PRO.ACCEPT-PAYMENT',
                'Description' => $orderNumber1c,
                'Amount' => $this->getDecimalAmount($amount),
                'CPFData' => null,
                'OfflineTran' => null,
                'ID' => $uuid,
                'ExtID' => $extId,
                'AuxDataInput' => [
                    'AuxData' => [
                        'Purchases' => $this->getPurchasesArray($products)
                    ]
                ]
            ]
        ];

        /** @var Response $response */
        /** @phpstan-ignore-next-line */
        $response = Http::ibox()->post(
            '/payment/submit',
            $body
        );

        if ($response->failed()) {
            throw new FiscalizationPaymentSubmitException();
        }

        return PaymentSubmitResponseData::from(
            $response->json()
        );
    }

    private function getDecimalAmount(Money $amount): string
    {
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        return $formatter->format($amount);
    }

    /**
     * @param Collection<OrderProductData> $orderProducts
     *
     * @return array
     */
    private function getPurchasesArray(Collection $orderProducts): array
    {
        return $orderProducts
            ->map(function (OrderProductData $product) {
                $purchase = [
                    'Title' => $this->getPurchaseTitle($product),
                    'Quantity' => $product->quantity,
                    'TaxCode' => [self::TAX_CODE],
                ];

                if (!is_null($product->promo_price)) {
                    $purchase['Price'] = $this->getDecimalAmount($product->promo_price);
                } else {
                    $purchase['Price'] = $this->getDecimalAmount($product->price);
                }

                return $purchase;
            })
            ->toArray();
    }

    private function getPurchaseTitle(OrderProductData $orderProduct): string
    {
        if (is_null($orderProduct->title)) {
            return $orderProduct->sku;
        }

        return $orderProduct->title;
    }
}
