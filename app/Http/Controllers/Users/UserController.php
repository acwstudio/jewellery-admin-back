<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Users\User\UpdateUserProfileData;
use App\Packages\DataObjects\Users\User\UserProfileData;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class UserController extends Controller
{
    public function __construct(
        protected readonly UsersModuleClientInterface $usersModuleClient
    ) {
    }

    #[Put(
        path: '/api/v1/user/profile',
        summary: 'Обновление профиля',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/users_user_update_profile_data')
        ),
        tags: ['User'],
        responses: [
            new Response(
                response: 200,
                description: 'Данные профиля пользователя',
                content: new JsonContent(ref: '#/components/schemas/users_user_profile_data')
            )
        ]
    )]
    public function updateProfile(UpdateUserProfileData $data): UserProfileData
    {
        return $this->usersModuleClient->updateProfile($data);
    }

    #[Get(
        path: '/api/v1/user/profile',
        summary: 'Получение профиля',
        tags: ['User'],
        responses: [
            new Response(
                response: 200,
                description: 'Данные профиля пользователя',
                content: new JsonContent(ref: '#/components/schemas/users_user_profile_data')
            )
        ]
    )]
    public function getProfile(): UserProfileData
    {
        return $this->usersModuleClient->getProfile();
    }
}
