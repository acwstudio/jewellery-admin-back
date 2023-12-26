<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Payment;

use App\Packages\ApiClients\Payment\Responses\Callbacks\SberbankCallbackStatusData;
use App\Packages\DataObjects\Payment\PaymentRequestData;
use App\Packages\ModuleClients\ApiSberClientInterface;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Voronkovich\SberbankAcquiring\Client;

class ApiSberClient implements ApiSberClientInterface
{
    /**
     * @param  string|null  $token
     */
    public function __construct(
        private readonly ?string $token = '',
    ) {
        $clientData['token'] = empty($this->token)
            ? (string)Config::get('sberbank-acquiring.auth.token')
            : $this->token;
        //        if(empty($this->token)) {
        //            $clientData = [
        //                'userName' => (string)Config::get('sberbank-acquiring.auth.userName'),
        //                'password' => (string)Config::get('sberbank-acquiring.auth.password'),
        //            ];
        //        }
        $isTestEnabled = config('sberbank-acquiring.test_enabled');
        $apiUri = $isTestEnabled ? Client::API_URI_TEST : Client::API_URI;
        $clientData['apiUri'] = $apiUri;
        $this->client = new Client($clientData);
    }
    public Client $client;

    public function getOrderStatusExtended(mixed $id): array
    {
        return $this->client->getOrderStatus(orderId: $id);
    }

    /**
     * Регистрация заказа
     *
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register
     *
     *
     * @throws InvalidArgumentException
     */
    public function registerOrder(PaymentRequestData $paymentRequestData): array
    {
        $returnUrl =
            config('sberbank-acquiring.params.return_url')
            . '?'
            . http_build_query(['order_id' => $paymentRequestData->orderId])
        ;

        return $this->client->registerOrder(
            $paymentRequestData->orderId,
            $paymentRequestData->amount,
            $returnUrl,
        );
    }

    public function isCorrectCheckSumCallback(SberbankCallbackStatusData $data): bool
    {
        $key = config('sberbank-acquiring.callback_status_key');
        if (null === $key) {
            return true;
        }

        $hash = $this->getHash($data->toArray(), $key);
        return strtoupper($data->checksum) === strtoupper($hash);
    }

    private function getHash(array $params, string $key): string
    {
        unset($params['checksum'], $params['sign_alias']);
        ksort($params);

        $data = '';
        foreach ($params as $key => $value) {
            $data .= $key . ';' . $value . ';';
        }

        return hash_hmac("sha256", $data, $key);
    }
}
