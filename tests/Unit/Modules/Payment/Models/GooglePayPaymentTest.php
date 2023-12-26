<?php

declare(strict_types=1);

namespace Modules\Payment\Models;

use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Helpers\Currency;
use App\Modules\Payment\Models\GooglePayPayment;
use App\Modules\Payment\Models\Payment;
use Tests\SberTestCase;

class GooglePayPaymentTest extends SberTestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function itHasBasePaymentRelation(): void
    {
        $acquiringPayment = $this->createAcquiringPayment(PaymentTypeEnum::GOOGLE_PAY);
        $googlePayPayment = $this->createSberbankPayment();
        $acquiringPayment->payment()->associate($googlePayPayment);
        $acquiringPayment->save();
        $basePayment = $googlePayPayment->basePayment;
        $this->assertInstanceOf(Payment::class, $basePayment);
    }

    /**
     * @test
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
            'amount'               => 20051,
            'currencyCode'         => Currency::USD,
            'email'                => 'test@test.test',
            'phone'                => '+7999999999',
            'returnUrl'            => 'http://test.com/api/success',
            'failUrl'              => 'http://test.com/api/error',
        ];
        $expectedAttributes = [
            'order_number'          => 'uvc124v',
            'description'           => 'operation description',
            'language'              => 'EN',
            'additional_parameters' => json_encode(['param' => 'value']),
            'pre_auth'              => 'false',
            'client_id'             => '1vc-21bvd21',
            'ip'                    => '10.10.10.10',
            'amount'                => 20051,
            'currency_code'         => Currency::USD,
            'email'                 => 'test@test.test',
            'phone'                 => '+7999999999',
            'return_url'            => 'http://test.com/api/success',
            'fail_url'              => 'http://test.com/api/error',
        ];
        $payment = new GooglePayPayment();
        $payment->fillWithSberbankParams($params);
        $this->assertEquals($expectedAttributes, $payment->getAttributes());
    }

    /**
     * @test
     */
    public function itHasNotFillablePaymentToken(): void
    {
        $this->assertEmpty((new GooglePayPayment())->payment_token);
    }

    /**
     * @test
     */
    public function testHiddenPaymentToken(): void
    {
        $payment = new GooglePayPayment();
        $payment->payment_token = 'some-string';
        $this->assertNotEmpty($payment->payment_token, 'Payment token should not be empty');
        $this->assertArrayNotHasKey(
            'payment_token',
            $payment->toArray(),
            'Payment token key should not exist in the array'
        );
    }

    /**
     * @test
     */
    public function testGetAndSetPaymentToken(): void
    {
        $payment = new GooglePayPayment();
        $this->assertEmpty($payment->getPaymentToken());
        $token = 'token-string';
        $payment->setPaymentToken($token);
        $this->assertEquals($token, $payment->getPaymentToken());
    }
}
