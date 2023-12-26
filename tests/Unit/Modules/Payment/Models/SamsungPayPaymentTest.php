<?php

declare(strict_types=1);

namespace Modules\Payment\Models;

use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\SamsungPayPayment;
use Tests\SberTestCase;

class SamsungPayPaymentTest extends SberTestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function testBasePaymentRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment(PaymentTypeEnum::SAMSUNG_PAY);
        $sberbankPayment = $this->createSberbankPayment();
        $acquiringPayment->payment()->associate($sberbankPayment);
        $acquiringPayment->save();
        $this->assertInstanceOf(Payment::class, $sberbankPayment->basePayment);
    }

    /**
     * @throws \JsonException
     */
    public function testItCanBeFilledWithSberbankAttributes(): void
    {
        $params = [
            'orderNumber'          => 'uvc124v',
            'description'          => 'operation description',
            'language'             => 'EN',
            'additionalParameters' => ['param' => 'value'],
            'preAuth'              => 'false',
            'clientId'             => '1vc-21bvd21',
            'ip'                   => '10.10.10.10',
        ];
        /** @phpstan-ignore-next-line */
        $payment = (new SamsungPayPayment())->getModel();
        $payment->fillWithSberbankParams($params);
        $expectedAttributes = [
            'order_number'          => 'uvc124v',
            'description'           => 'operation description',
            'language'              => 'EN',
            'additional_parameters' => json_encode(['param' => 'value'], JSON_THROW_ON_ERROR),
            'pre_auth'              => 'false',
            'client_id'             => '1vc-21bvd21',
            'ip'                    => '10.10.10.10',
        ];
        $this->assertEquals($expectedAttributes, $payment->getAttributes());
    }

    /**
     * @test
     */
    public function itHasNotFillablePaymentToken(): void
    {
        /** @phpstan-ignore-next-line */
        $payment = (new SamsungPayPayment())->getModel();

        $payment->setPaymentToken('some-string');

        $this->assertNotEmpty($payment->getPaymentToken());
    }

    /**
     * @test
     */
    public function itHasHiddenPaymentToken(): void
    {
        /** @phpstan-ignore-next-line */
        $payment = (new SamsungPayPayment())->getModel();
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
        $payment = (new SamsungPayPayment())->getModel();
        $this->assertEmpty($payment->getPaymentToken());
        $token = 'token-string';
        $payment->setPaymentToken($token);
        $this->assertEquals($token, $payment->getPaymentToken());
    }
}
