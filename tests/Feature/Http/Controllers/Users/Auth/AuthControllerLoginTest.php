<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\Auth;

use App\Modules\OTP\Models\OtpVerification;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Support\PhoneNumber;
use Laravel\Sanctum\Sanctum;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class AuthControllerLoginTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/auth/login';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser(RoleEnum::USER);
    }

    public function testSuccessful()
    {
        $response = $this->post(self::METHOD, [
            'email' => $this->user->email,
            'password' => '123456'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('auth', $content);
        self::assertIsArray($content['auth']);
        self::assertNotEmpty($content['auth']);
        self::assertArrayHasKey('access_token', $content['auth']);
    }

    public function testSuccessfulByPhone()
    {
        $phoneFormat = PhoneNumberUtil::getInstance()->format(
            $this->user->phone,
            PhoneNumberFormat::E164
        );

        /** @var OtpVerification $otpVerification */
        $otpVerification = OtpVerification::factory()->create([
            'phone' => $this->user->phone,
            'code' => '123456'
        ]);

        $response = $this->post(self::METHOD, [
            'phone' => $phoneFormat,
            'otp_id' => $otpVerification->id,
            'otp_code' => '123456'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('auth', $content);
        self::assertIsArray($content['auth']);
        self::assertNotEmpty($content['auth']);
        self::assertArrayHasKey('access_token', $content['auth']);
    }

    public function testSuccessfulRegisterByPhone()
    {
        $phone = '+79087799489';

        /** @var OtpVerification $otpVerification */
        $otpVerification = OtpVerification::factory()->create([
            'phone' => PhoneNumberUtil::getInstance()->parse(
                $phone,
                'RU',
                new PhoneNumber()
            ),
            'code' => '123456'
        ]);

        $response = $this->post(self::METHOD, [
            'phone' => $phone,
            'otp_id' => $otpVerification->id,
            'otp_code' => '123456'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('auth', $content);
        self::assertIsArray($content['auth']);
        self::assertNotEmpty($content['auth']);
        self::assertArrayHasKey('access_token', $content['auth']);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureIncorrectPassword()
    {
        $response = $this->post(self::METHOD, [
            'email' => $this->user->email,
            'password' => 'incorrect'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAuth()
    {
        Sanctum::actingAs($this->user);

        $response = $this->post(self::METHOD, [
            'email' => $this->user->email,
            'password' => '123456'
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyPassword()
    {
        $response = $this->post(self::METHOD, [
            'email' => $this->user->email,
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyEmail()
    {
        $response = $this->post(self::METHOD, [
            'password' => '123456'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyPhone()
    {
        $response = $this->post(self::METHOD, [
            'otp_id' => '123456',
            'otp_code' => '123456'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyOtpId()
    {
        $response = $this->post(self::METHOD, [
            'phone' => '+79087799489',
            'otp_code' => '123456'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyOtpCode()
    {
        $response = $this->post(self::METHOD, [
            'phone' => '+79087799489',
            'otp_id' => '123456'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
