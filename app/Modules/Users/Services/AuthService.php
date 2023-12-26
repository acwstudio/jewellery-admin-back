<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;
use App\Modules\Users\Repositories\RoleRepository;
use App\Modules\Users\Repositories\UserRepository;
use App\Modules\Users\Support\Blueprints\AuthBlueprint;
use App\Modules\Users\Support\Blueprints\CreateUserBlueprint;
use App\Modules\Users\Support\Blueprints\OAuthBlueprint;
use App\Packages\ApiClients\OAuth\Contracts\OAuthApiClientContract;
use App\Packages\ApiClients\OAuth\Responses\Yandex\DefaultPhoneData;
use App\Packages\DataObjects\OTP\CheckOtpVerificationData;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\ModuleClients\OtpModuleClientInterface;
use App\Packages\Support\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use libphonenumber\PhoneNumberUtil;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository,
        private readonly OtpModuleClientInterface $otpModuleClient,
        private readonly OAuthApiClientContract $oauthApiClient
    ) {
    }

    public function login(AuthBlueprint $authBlueprint): ?User
    {
        if ($authBlueprint->phone instanceof PhoneNumber) {
            return $this->getUserByPhone($authBlueprint->phone, $authBlueprint->otp_id, $authBlueprint->otp_code);
        }

        if (!Auth::attempt(['email' => $authBlueprint->email, 'password' => $authBlueprint->password])) {
            return null;
        }

        return $this->userRepository->getByEmail($authBlueprint->email);
    }

    public function oauth(OAuthBlueprint $oauthBlueprint): ?User
    {
        $yandexClient = $this->oauthApiClient->yandexInfo($oauthBlueprint->token);

        if ($yandexClient->default_phone instanceof DefaultPhoneData) {
            /** @var PhoneNumber $phone */
            $phone = PhoneNumberUtil::getInstance()->parse(
                $yandexClient->default_phone->number,
                'RU',
                new PhoneNumber()
            );

            return $this->getOrRegisterByPhone($phone);
        }

        if (!empty($yandexClient->default_email)) {
            return $this->userRepository->getByEmail($yandexClient->default_email);
        }

        return null;
    }

    private function getUserByPhone(PhoneNumber $phone, ?string $otpId = null, ?string $otpCode = null): User
    {
        if (empty($otpId) || empty($otpCode)) {
            throw new \RuntimeException('Недостаточно данных для верификации');
        }

        $isValid = $this->otpModuleClient->check(
            new CheckOtpVerificationData($otpId, $phone, $otpCode)
        );

        if (!$isValid) {
            throw new \RuntimeException('Неверный код верификации');
        }

        return $this->getOrRegisterByPhone($phone);
    }

    private function getOrRegisterByPhone(PhoneNumber $phone): User
    {
        $user = $this->userRepository->getByPhone($phone);

        if ($user instanceof User) {
            return $user;
        }

        $roleUser = $this->roleRepository->getOrCreateByRole(RoleEnum::USER);
        return $this->userRepository->create(
            new CreateUserBlueprint($phone),
            $roleUser
        );
    }
}
