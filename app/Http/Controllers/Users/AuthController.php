<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Users\Auth\AuthLoginData;
use App\Packages\DataObjects\Users\Auth\AuthOauthData;
use App\Packages\DataObjects\Users\User\UserData;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class AuthController extends Controller
{
    public function __construct(
        protected readonly UsersModuleClientInterface $usersModuleClient
    ) {
    }

    #[Post(
        path: '/api/v1/auth/login',
        summary: 'Авторизация (и регистрация по телефону)',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/auth_login_data')
        ),
        tags: ['Auth'],
        responses: [
            new Response(
                response: 200,
                description: 'Данные пользователя',
                content: new JsonContent(ref: '#/components/schemas/users_user_data')
            )
        ]
    )]
    public function login(AuthLoginData $data): UserData
    {
        return $this->usersModuleClient->login($data);
    }

    #[Post(
        path: '/api/v1/auth/logout',
        summary: 'Выйти из учетной записи',
        tags: ['Auth'],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function logout(): \Illuminate\Http\Response
    {
        $this->usersModuleClient->logout();
        return \response('');
    }

    #[Post(
        path: '/api/v1/auth/oauth',
        summary: 'Авторизация по OAuth',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/auth_oauth_data')
        ),
        tags: ['Auth'],
        responses: [
            new Response(
                response: 200,
                description: 'Данные пользователя',
                content: new JsonContent(ref: '#/components/schemas/users_user_data')
            )
        ]
    )]
    public function oauth(AuthOauthData $data): UserData
    {
        return $this->usersModuleClient->oauth($data);
    }
}
