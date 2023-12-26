<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Users\Models;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\Role;
use App\Packages\Enums\Users\RoleEnum;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function testSuccessful()
    {
        $user = User::factory()->create();

        self::assertInstanceOf(User::class, $user);
    }

    public function testSuccessfulByRole()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->addRole($user);

        self::assertInstanceOf(User::class, $user);
        self::assertInstanceOf(Role::class, $user->roles()->first());
    }

    public function testSuccessfulByAdminRole()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->addRole($user, RoleEnum::ADMIN);

        self::assertInstanceOf(User::class, $user);
        self::assertInstanceOf(Role::class, $user->roles()->first());
        self::assertEquals(RoleEnum::ADMIN->value, $user->roles()->first()->type->value);
    }

    public function testSuccessfulDuplicate()
    {
        User::factory()->create(['phone' => null]);
        $user = User::factory()->create(['phone' => null]);

        self::assertInstanceOf(User::class, $user);
    }

    public function testFailureDuplicate()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->expectException(\Exception::class);

        User::factory()->create(['phone' => $user->phone]);
    }

    private function addRole(User $user, ?RoleEnum $role = null): void
    {
        /** @var Role $role */
        $role = Role::factory()->create([
            'type' => $role ?? RoleEnum::USER
        ]);

        $user->roles()->sync([$role->id]);
        $user->save();
    }
}
