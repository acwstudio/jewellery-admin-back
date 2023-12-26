<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Users\Services;

use App\Modules\Catalog\Support\Pagination;
use App\Modules\Users\Models\User;
use App\Modules\Users\Models\Role;
use App\Modules\Users\Services\UserService;
use App\Modules\Users\Support\Blueprints\CreateUserBlueprint;
use App\Modules\Users\Support\Blueprints\UpdateUserBlueprint;
use App\Packages\Support\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    public function testSuccessfulGetUser()
    {
        /** @var User $userCreate */
        $userCreate = User::factory()->create();

        $user = $this->userService->getUser($userCreate->user_id);
        self::assertInstanceOf(User::class, $user);
    }

    public function testSuccessfulGetList()
    {
        User::factory(5)->create();

        $pagination = new Pagination(1, 10);
        $users = $this->userService->getUsers($pagination)->items();

        self::assertCount(5, $users);
        foreach ($users as $user) {
            self::assertInstanceOf(User::class, $user);
        }
    }

    public function testSuccessfulCreateUser()
    {
        /** @var PhoneNumber $phone */
        $phone = PhoneNumberUtil::getInstance()->parse(
            '+79087799488',
            'RU',
            new PhoneNumber()
        );

        /** @var Role $role */
        $role = Role::factory()->create();

        $data = new CreateUserBlueprint(
            $phone,
            'Test',
            'test@mail.ru',
            '12345678'
        );
        $user = $this->userService->createUser($data, $role->id);

        self::assertInstanceOf(User::class, $user);
        self::assertCount(1, $user->roles()->get());
        self::assertInstanceOf(Role::class, $user->roles()->first());
    }

    public function testSuccessfulUpdateUser()
    {
        /** @var Role $role */
        $role = Role::factory()->create();
        /** @var User $user */
        $user = User::factory()->create();
        $user->roles()->sync([$role->id]);

        $userName = $user->name;

        $data = new UpdateUserBlueprint(
            'Test',
            null,
            null,
            null,
            null,
            $user->email,
            $user->password
        );
        $user = $this->userService->updateUser($user, $data, $role->id);
        $user->refresh();

        self::assertInstanceOf(User::class, $user);
        self::assertCount(1, $user->roles()->get());
        self::assertInstanceOf(Role::class, $user->roles()->first());
        self::assertNotEquals($userName, $user->name);
    }

    public function testSuccessfulDeleteUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->userService->deleteUser($user->user_id);
        $user = $this->userService->getUser($user->user_id);

        self::assertEmpty($user);
    }
}
