<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\IntellinSms;

use App\Packages\ApiClients\IntellinSms\Responses\SendIntellinSmsResponseData;
use App\Packages\Exceptions\IntellinSmsSendException;
use Illuminate\Support\Facades\Http;

class SmsSender
{
    /**
     * @param string $phone
     * @param string $message
     * @return SendIntellinSmsResponseData
     * @throws IntellinSmsSendException
     */
    public function sendSms(string $phone, string $message): SendIntellinSmsResponseData
    {
        $data = [
            'http_username' => env('INTELLIN_SMS_USER'),
            'http_password' => env('INTELLIN_SMS_PASS'),
            'phone_list' => $phone,
            'message' => $message,
            'format' => 'json',
        ];

        try {
            $response = Http::intellin()->timeout(10)->get('/sendsms.cgi', $data);
        } catch (\Exception) {
            throw new IntellinSmsSendException();
        }

        return SendIntellinSmsResponseData::from($response->json());
    }
}
