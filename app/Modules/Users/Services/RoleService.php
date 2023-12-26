<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\Role;
use App\Modules\Users\Repositories\RoleRepository;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Collection;

class RoleService
{
    public function __construct(
        private readonly RoleRepository $roleRepository
    ) {
    }

    public function getRole(int $id): ?Role
    {
        return $this->roleRepository->getById($id);
    }

    public function getRoles(): Collection
    {
        return $this->roleRepository->getList();
    }

    public function createRole(RoleEnum $roleEnum): Role
    {
        return $this->roleRepository->create($roleEnum);
    }

    public function deleteRole(int $id): void
    {
        $role = $this->roleRepository->getById($id, true);
        $this->roleRepository->delete($role);
    }
}
