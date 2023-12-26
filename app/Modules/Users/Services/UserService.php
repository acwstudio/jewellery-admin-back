<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Catalog\Support\Pagination;
use App\Modules\Users\Enums\SexTypeEnum;
use App\Modules\Users\Exceptions\OldPasswordNotValidException;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use App\Modules\Users\Repositories\RoleRepository;
use App\Modules\Users\Repositories\UserRepository;
use App\Modules\Users\Support\Blueprints\CreateUserBlueprint;
use App\Modules\Users\Support\Blueprints\UpdateUserBlueprint;
use App\Packages\DataObjects\Users\User\ImportUsersData;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Events\UserCreated;
use App\Packages\Support\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository
    ) {
    }

    public function getUser(string $userId): ?User
    {
        return $this->userRepository->getByUserId($userId);
    }

    public function getUsers(Pagination $pagination): LengthAwarePaginator
    {
        return $this->userRepository->getList($pagination);
    }

    public function createUser(
        CreateUserBlueprint $userBlueprint,
        Role|int $role
    ): User {
        if (is_int($role)) {
            $role = $this->roleRepository->getById($role, true);
        }

        $user = $this->userRepository->create(
            $userBlueprint,
            $role
        );

        UserCreated::dispatch($user->user_id);

        return $user;
    }

    public function updateUser(
        User|string $user,
        UpdateUserBlueprint $updateUserBlueprint,
        Role|int|null $role = null
    ): User {
        if (is_string($user)) {
            $user = $this->userRepository->getByUserId($user, true);
        }

        if (is_int($role)) {
            $role = $this->roleRepository->getById($role, true);
        }

        if ($updateUserBlueprint->old_password && $updateUserBlueprint->password) {
            $this->userRepository->updatePassword(
                $user,
                $updateUserBlueprint->old_password,
                $updateUserBlueprint->password
            );
        }

        $this->userRepository->update(
            $user,
            $updateUserBlueprint,
            $role
        );

        return $user->refresh();
    }

    public function deleteUser(string $userId): void
    {
        $user = $this->userRepository->getByUserId($userId, true);
        $this->userRepository->delete($user);
    }

    public function importUser(ImportUsersData $userData): void
    {
        $roleUser = $this->roleRepository->getOrCreateByRole(RoleEnum::USER);
        /** @var PhoneNumber $phone */
        $phone = $userData->phone;
        $user = $this->userRepository->create(
            new CreateUserBlueprint($phone, $userData->first_name, $userData->email, null),
            $roleUser
        );

        $this->userRepository->update(
            $user,
            new UpdateUserBlueprint(
                $userData->first_name,
                $userData->last_name,
                $userData->second_name,
                null,
                null,
                $userData->email,
                null,
                null,
            ),
            $roleUser
        );
    }
}
