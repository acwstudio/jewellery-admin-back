<?php

declare(strict_types=1);

namespace Modules\Payment\Models;

use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Models\ApplePayPayment;
use App\Modules\Payment\Models\Payment;
use Tests\SberTestCase;

class ApplePayPaymentTest extends SberTestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function itHasBasePaymentRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment(PaymentTypeEnum::APPLE_PAY);
        $applePayPayment = $this->createApplePayPayment();
        $acquiringPayment->payment()->associate($applePayPayment);
        $acquiringPayment->save();
        $this->assertInstanceOf(Payment::class, $applePayPayment->basePayment);
    }

    /**
     * @test
     * @throws \JsonException
     */
    public function itCanBeFilledWithSberbankAttributes(): void
    {
        $params = [
            'orderNumber'          => '9mvc5211',
            'description'          => 'operation description',
            'language'             => 'EN',
            'additionalParameters' => ['param' => 'value'],
            'preAuth'              => 'false',
        ];
        $expectedAttributes = [
            'order_number'          => '9mvc5211',
            'description'           => 'operation description',
            'language'              => 'EN',
            'additional_parameters' => json_encode(['param' => 'value'], JSON_THROW_ON_ERROR),
            'pre_auth'              => 'false',
        ];
        $payment = new ApplePayPayment();
        $payment->fillWithSberbankParams($params);
        $this->assertEquals($expectedAttributes, $payment->getAttributes());
    }

    /**
     * @test
     */
    public function itHasNotFillablePaymentToken(): void
    {
        $payment = new ApplePayPayment();
        $payment->fill(['payment_token' => 'some-string']);
        $this->assertEmpty($payment->getPaymentToken());
    }

    /**
     * @test
     */
    public function itHasHiddenPaymentToken(): void
    {
        $payment = new ApplePayPayment();

        $payment->payment_token = 'some-string';

        $this->assertNotEmpty($payment->payment_token);
        $this->assertArrayNotHasKey('payment_token', $payment->toArray());
    }

    /**
     * @test
     */
    public function testGetAndSetPaymentToken(): void
    {
        /** @phpstan-ignore-next-line */
        $payment = (new ApplePayPayment())->getModel();
        $token = $payment->getPaymentToken();
        $this->assertEmpty($token);
        $token = 'token-string';
        $payment->setPaymentToken($token);
        $this->assertEquals($token, $payment->getPaymentToken());
    }
}
