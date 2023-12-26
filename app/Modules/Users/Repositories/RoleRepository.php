<?php

declare(strict_types=1);

namespace App\Modules\Users\Repositories;

use App\Modules\Users\Models\Role;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class RoleRepository
{
    public function getById(int $id, bool $fail = false): ?Role
    {
        $query = Role::query();

        if ($fail) {
            /** @var Role $role */
            $role = $query->findOrFail($id);
            return $role;
        }

        /** @var Role $role */
        $role = $query->find($id);
        return $role;
    }

    public function getOrCreateByRole(RoleEnum $roleEnum): Role
    {
        $role = Role::query()->where('type', '=', $roleEnum)->first();

        if (!$role instanceof Role) {
            $role = $this->create($roleEnum);
        }

        return $role;
    }

    public function getList(bool $fail = false): Collection
    {
        $query = Role::query();

        if ($fail && $query->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $query->get();
    }

    public function create(
        RoleEnum $roleEnum
    ): Role {
        $role = new Role([
            'type' => $roleEnum,
        ]);

        $role->save();

        return $role;
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }
}
