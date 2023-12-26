<?php

declare(strict_types=1);

namespace App\Modules\Orders\UseCase;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Services\DeliveryService;
use App\Modules\Orders\Services\OrderService;
use App\Modules\Orders\Services\PersonalDataService;
use App\Modules\Orders\Services\ProductService;
use App\Modules\Orders\Services\PromocodeService;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Payment\Helpers\Currency;
use App\Packages\DataObjects\Checkout\Summary\GetSummaryData;
use App\Packages\DataObjects\Checkout\Summary\GetSummaryDeliveryData;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderDeliveryData;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderPersonalData;
use App\Packages\DataObjects\Payment\PaymentRequestData;
use App\Packages\DataObjects\Payment\PaymentRequestParams;
use App\Packages\Events\OrderCreated;
use App\Packages\Exceptions\Delivery\CurrierDeliveryNotAvailableException;
use App\Packages\Exceptions\Orders\CreateOrderException;
use App\Packages\ModuleClients\CheckoutModuleClientInterface;
use App\Packages\ModuleClients\PaymentModuleClientInterface;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use Money\Money;
use Psr\Log\LoggerInterface;
use Throwable;

class CreateOrder
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly PersonalDataService $personalDataService,
        private readonly DeliveryService $deliveryService,
        private readonly ProductService $productService,
        private readonly CheckoutModuleClientInterface $checkoutModuleClient,
        private readonly LoggerInterface $logger,
        private readonly PromocodeService $promocodeService,
        private readonly ShopCartModuleClientInterface $shopCartModuleClient,
        private readonly PaymentModuleClientInterface $paymentModuleClient,
    ) {
    }

    /**
     * @throws CreateOrderException
     */
    public function __invoke(
        string $project,
        string $country,
        string $currency,
        PaymentTypeEnum $paymentType,
        ?string $comment,
        CreateOrderPersonalData $personalData,
        CreateOrderDeliveryData $deliveryData,
    ): Order {
        try {
            $order = $this->createOrder(
                $project,
                $country,
                $currency,
                $paymentType,
                $comment,
                $personalData,
                $deliveryData,
            );
        } catch (Throwable $e) {
            $this->logger->alert(
                '[x] Create order failed!',
                ['exception' => $e, 'data' => func_get_args()],
            );

            throw new CreateOrderException();
        }

        OrderCreated::dispatch(
            $order->id,
        );

        $this->logger->info("[+] Order #$order->id successfully crated");

        return $order;
    }

    /**
     * @throws CurrierDeliveryNotAvailableException|Throwable
     */
    private function createOrder(
        string $project,
        string $country,
        string $currency,
        PaymentTypeEnum $paymentType,
        ?string $comment,
        CreateOrderPersonalData $personalData,
        CreateOrderDeliveryData $deliveryData,
    ): Order {
        $summary = $this->getSummary($deliveryData);

        $promotionExternalId = $this->promocodeService->getPromotionExternalId();
        $shopCart = $this->shopCartModuleClient->getShopCart();

        $order = $this->orderService->create(
            $project,
            $country,
            $currency,
            $paymentType,
            $summary,
            $comment,
            $promotionExternalId,
            $shopCart->token,
        );

        $this->createPersonalData(
            $order,
            $personalData,
        );

        $this->createDelivery($order, $deliveryData);

        $this->createOrderProducts($order);
        return $this->getOrderWithPayment($order, $paymentType);
    }

    private function getOrderWithPayment($order, $paymentType): Order
    {
        $orderPaymentData['payment_type'] = $paymentType;
        $orderPaymentData['payment_id'] = null;
        if ($paymentType === PaymentTypeEnum::SBER_PAY) {
            $payment = $this->paymentModuleClient->register($this->getPaymentRequestData($order));
            $orderPaymentData['payment_id'] = $payment->payment_id;
        }
        $order->update($orderPaymentData);
        return $order->refresh();
    }

    private function createPersonalData(Order $order, CreateOrderPersonalData $personalData): void
    {
        $this->personalDataService->create($order, $personalData);
    }

    /**
     * @throws CurrierDeliveryNotAvailableException
     */
    private function createDelivery(Order $order, CreateOrderDeliveryData $deliveryOptions): void
    {
        $this->deliveryService->create($order, $deliveryOptions);
    }

    private function createOrderProducts(Order $order): void
    {
        $this->productService->create($order);
    }

    private function getSummary(CreateOrderDeliveryData $deliveryData): Money
    {
        $getSummaryData = new GetSummaryData(
            new GetSummaryDeliveryData(
                $deliveryData->currierDeliveryId,
                $deliveryData->pvzId,
            ),
        );

        $summary = $this->checkoutModuleClient->getSummary($getSummaryData)->summary;


        return $this->getFinalSummary($summary);
    }

    private function getFinalSummary(Money $summary): Money
    {
        $final = (int)((int)$summary->getAmount() / 100);
        $final *= 100;
        return Money::RUB($final);
    }

    private function getPaymentRequestData(Order $order): PaymentRequestData
    {
        return new PaymentRequestData(
            orderId: (int)$order->id,
            amount: (int)$order->summary->getAmount(),
            params: new PaymentRequestParams(
                order_number: (string)$order->id,
                amount: (int)$order->summary->getAmount(),
                currency: Currency::RUB,
                return_url: config('sberbank-acquiring.params.return_url'),
                fail_url: '',
                description: '',
                client_id: $order->user_id,
                features: '',
                bank_form_url: '',
                language: 'RU',
                page_view: '',
                json_params: '{}',
                expiration_date: now()->addDays(1)->format('Y-m-d H:i:s'),
            ),
        );
    }
}
