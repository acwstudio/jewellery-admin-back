<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\DataObjects\Users\User\UpdateUserProfileData;
use App\Packages\Events\OrderCreated;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Psr\Log\LoggerInterface;

class UpdateUserData
{
    public function __construct(
        private readonly OrdersModuleClientInterface $ordersModuleClient,
        private readonly UsersModuleClientInterface $usersModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handle(OrderCreated $event): void
    {
        try {
            $user = $this->usersModuleClient->getUser();
            $order = $this->ordersModuleClient->getOrder($event->orderId);

            $this->usersModuleClient->updateProfile(
                new UpdateUserProfileData(
                    $user->phone,
                    $order->personalData->name,
                    $order->personalData->email,
                    $user->sex,
                    $user->birth_date?->toString(),
                    $order->personalData->surname,
                    $order->personalData->patronymic
                )
            );
        } catch (\Throwable $e) {
            $this->logger->error('[UpdateUserDataListener] Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'order_id' => $event->orderId
            ]);
        }
    }
}
