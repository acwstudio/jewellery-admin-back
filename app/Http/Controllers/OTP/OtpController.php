<?php

declare(strict_types=1);

namespace App\Http\Controllers\OTP;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\OTP\OtpVerificationData;
use App\Packages\DataObjects\OTP\SendOtpVerificationData;
use App\Packages\ModuleClients\OtpModuleClientInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class OtpController extends Controller
{
    public function __construct(
        protected readonly OtpModuleClientInterface $otpModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/otp/send',
        summary: 'Отправка кода верификации',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/otp_send_otp_verification_data')
        ),
        tags: ['OTP'],
        responses: [
            new Response(
                response: 200,
                description: 'OTP данные',
                content: new JsonContent(ref: '#/components/schemas/otp_otp_verification_data')
            )
        ],
    )]
    public function send(SendOtpVerificationData $data): OtpVerificationData
    {
        return $this->otpModuleClient->send($data);
    }
}
