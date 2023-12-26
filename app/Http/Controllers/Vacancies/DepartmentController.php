<?php

declare(strict_types=1);

namespace App\Http\Controllers\Vacancies;

use App\Http\Controllers\Controller;
use App\Modules\Vacancies\VacancyModuleClient;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Vacancies\CreateDepartmentData;
use App\Packages\DataObjects\Vacancies\DepartmentData;
use App\Packages\DataObjects\Vacancies\UpdateDepartmentData;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class DepartmentController extends Controller
{
    public function __construct(
        protected readonly VacancyModuleClient $vacancyModuleClient
    ) {
    }
    #[Get(
        path: '/api/v1/vacancies/department',
        summary: 'Получить список департаментов',
        tags: ['Departments'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция департаментов',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(
                        ref: '#/components/schemas/department_data'
                    )
                )
            )
        ]
    )]
    public function index(): Collection
    {
        return $this->vacancyModuleClient->getAllDepartments();
    }

    #[Get(
        path: '/api/v1/vacancies/department/{id}',
        summary: 'Получить департамент по id',
        tags: ['Departments'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Департамент',
                content: new JsonContent(ref: '#/components/schemas/department_data')
            )
        ]
    )]
    public function show(int $id): DepartmentData
    {
        return $this->vacancyModuleClient->getDepartmentById($id);
    }

    #[Post(
        path: '/api/v1/vacancies/department',
        summary: 'Создание департамента',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_department_data')
        ),
        tags: ['Departments'],
        responses: [
            new Response(
                response: 200,
                description: 'Департамент',
                content: new JsonContent(ref: '#/components/schemas/department_data')
            )
        ]
    )]
    public function create(CreateDepartmentData $departmentData): DepartmentData
    {
        return $this->vacancyModuleClient->createDepartment($departmentData);
    }

    #[Put(
        path: '/api/v1/vacancies/department',
        summary: 'Обновление департамента',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/update_department_data')
        ),
        tags: ['Departments'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Департамент',
                content: new JsonContent(ref: '#/components/schemas/department_data')
            )
        ]
    )]
    public function update(int $id, UpdateDepartmentData $departmentData): DepartmentData
    {
        return $this->vacancyModuleClient->updateDepartment($id, $departmentData);
    }

    #[Delete(
        path: '/api/v1/vacancies/department/{id}',
        summary: 'Удалить департамент',
        tags: ['Departments'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Успешно удалено',
                content: new JsonContent(ref: '#/components/schemas/success_data')
            )
        ]
    )]
    public function destroy(int $id): SuccessData
    {
        return $this->vacancyModuleClient->deleteDepartmentById($id);
    }
}
