<?php

declare(strict_types=1);

namespace App\Modules\Users\UseCases;

use App\Modules\Users\Services\UsersImportService;
use App\Modules\Users\Services\UserService;
use App\Packages\DataObjects\Users\User\ImportUsersData;
use Psr\Log\LoggerInterface;

class ImportUsers
{
    public function __construct(
        private readonly UsersImportService $usersImportService,
        private readonly UserService $userService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        try {
            $dataList = $this->usersImportService->import();
        } catch (\Throwable $e) {
            $this->logger->error(
                "Get users from Monolith error",
                ['exception' => $e]
            );
            return;
        }



        /** @var ImportUsersData $data */
        foreach ($dataList as $data) {
            try {
                $this->userService->importUser($data);
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Users with phone: $data->phone import error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        }
    }
}
