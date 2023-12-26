<?php

declare(strict_types=1);

namespace Tests;

use App\Modules\Users\Models\Role;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\ModuleClients\AMQPModuleClientInterface;
use App\Packages\Support\PhoneNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;
use libphonenumber\PhoneNumberUtil;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    public bool $dropTypes = true;

    protected function getTestResources(string $path = ''): string
    {
        return __DIR__ . '/Resources/' . rtrim($path, '/');
    }

    public function sendByFiles(
        string $method,
        string $uri,
        array $data = [],
        array $files = [],
        array $headers = []
    ): TestResponse {
        $server = $this->transformHeadersToServerVars($headers);
        $cookies = $this->prepareCookiesForRequest();

        return $this->call($method, $uri, $data, $cookies, $files, $server);
    }

    public static function setParamsInString(array $params, string $subject): string
    {
        foreach ($params as $param => $value) {
            $subject = str_replace('{' . $param . '}', (string)$value, $subject);
        }

        return $subject;
    }

    public function getUser(?RoleEnum $roleEnum = null): User
    {
        $roleEnum = $roleEnum ?? RoleEnum::USER;

        $role = Role::query()
            ->where('type', '=', $roleEnum->value)
            ->first();

        if (!$role instanceof Role) {
            $role = Role::factory()->create(['type' => $roleEnum]);
        }

        /** @var User $user */
        $user = User::factory()->create();
        $user->roles()->attach($role);
        $user->save();

        return $user;
    }

    public function getPhoneNumber(string $phone): ?PhoneNumber
    {
        /** @var PhoneNumber|null $phone */
        $phone = PhoneNumberUtil::getInstance()->parse(
            $phone,
            'RU',
            new PhoneNumber()
        );

        return $phone;
    }

    public function mockAMQPModuleClient(array $message): void
    {
        $this->partialMock(AMQPModuleClientInterface::class, function (MockInterface $mock) use ($message) {
            /** @var \Mockery\Expectation $mockConsume */
            $mockConsume = $mock->shouldReceive('consume');
            $mockConsume->andReturnUsing(function (string $queue, \Closure $callback) use ($message) {
                $callback($message);
            });

            $mock->shouldReceive('publish');
        });
    }
}
