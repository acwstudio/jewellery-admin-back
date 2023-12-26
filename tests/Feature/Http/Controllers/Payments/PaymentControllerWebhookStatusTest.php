<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Payments;

use App\Modules\Payment\Models\Payment;
use App\Packages\ApiClients\Payment\Enums\OperationEnum;
use Tests\TestCase;

class PaymentControllerWebhookStatusTest extends TestCase
{
    private const METHOD = '/api/v1/payments/webhook/status';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockAMQPModuleClient([]);
    }

    public function testSuccessful()
    {
        $data = [
            'mdOrder' => '11f321d8-efe0-7dbb-9df7-1db72951e10e',
            'orderNumber' => '704025',
            'operation' => OperationEnum::DEPOSITED->value,
            'status' => '1',
            'checksum' => '357346E80D2F873141F11D8D77EC86876E0F45C5126C2742826B75FB6254E2D3'
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    public function testSuccessfulPayment()
    {
        /** @var Payment $payment */
        $payment = Payment::factory()->create();
        $data = [
            'mdOrder' => $payment->bank_order_id,
            'orderNumber' => $payment->payment->order_number,
            'operation' => OperationEnum::DEPOSITED->value,
            'status' => '1',
            'checksum' => '357346E80D2F873141F11D8D77EC86876E0F45C5126C2742826B75FB6254E2D3'
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }
}
