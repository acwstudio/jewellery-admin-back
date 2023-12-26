<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Recaptcha;

use App\Packages\ApiClients\Recaptcha\Responses\RecaptchaSiteVerifyResponseData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RecaptchaApiClient
{
    public function siteVerify(string $response): RecaptchaSiteVerifyResponseData
    {
        if (config('recaptcha.debug_mode')) {
            return RecaptchaSiteVerifyResponseData::from([
                'success' => true,
                'error-codes' => []
            ]);
        }

        $body = [
            'secret' => config('recaptcha.secret_key'),
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        /** @var Response $response */
        /** @phpstan-ignore-next-line */
        $response = Http::recaptcha()->post('/siteverify', $body);

        if ($response->failed()) {
            throw new \Exception('Ошибка проверки Recaptcha');
        }

        return RecaptchaSiteVerifyResponseData::from(
            $response->json()
        );
    }
}
