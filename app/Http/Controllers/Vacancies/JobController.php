<?php

declare(strict_types=1);

namespace App\Http\Controllers\Vacancies;

use App\Http\Controllers\Controller;
use App\Modules\Vacancies\VacancyModuleClient;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Vacancies\CreateJobData;
use App\Packages\DataObjects\Vacancies\CreateVacancyApplyData;
use App\Packages\DataObjects\Vacancies\JobData;
use App\Packages\DataObjects\Vacancies\UpdateJobData;
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

class JobController extends Controller
{
    public function __construct(
        protected readonly VacancyModuleClient $vacancyModuleClient
    ) {
    }
    #[Get(
        path: '/api/v1/vacancies/vacancy',
        summary: 'Получить список вакансий',
        tags: ['Jobs'],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция вакансий',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(
                        ref: '#/components/schemas/job_data'
                    )
                )
            )
        ]
    )]
    public function index(): Collection
    {
        return $this->vacancyModuleClient->getAllJobs();
    }

    #[Get(
        path: '/api/v1/vacancies/vacancy/{id}',
        summary: 'Получить вакансия по id',
        tags: ['Jobs'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Вакансия',
                content: new JsonContent(ref: '#/components/schemas/job_data')
            )
        ]
    )]
    public function show(int $id): JobData
    {
        return $this->vacancyModuleClient->getJobById($id);
    }

    #[Post(
        path: '/api/v1/vacancies/vacancy',
        summary: 'Создание вакансии',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_job_data')
        ),
        tags: ['Jobs'],
        responses: [
            new Response(
                response: 200,
                description: 'Вакансия',
                content: new JsonContent(ref: '#/components/schemas/job_data')
            )
        ]
    )]
    public function create(CreateJobData $jobData): JobData
    {
        return $this->vacancyModuleClient->createJob($jobData);
    }

    #[Put(
        path: '/api/v1/vacancies/vacancy',
        summary: 'Обновление вакансия',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/update_job_data')
        ),
        tags: ['Jobs'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Вакансия',
                content: new JsonContent(ref: '#/components/schemas/job_data')
            )
        ]
    )]
    public function update(int $id, UpdateJobData $jobData): JobData
    {
        return $this->vacancyModuleClient->updateJob($id, $jobData);
    }

    #[Delete(
        path: '/api/v1/vacancies/vacancy/{id}',
        summary: 'Удалить вакансию',
        tags: ['Jobs'],
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
        return $this->vacancyModuleClient->deleteJobById($id);
    }

    #[Post(
        path: '/api/v1/vacancies/vacancy/apply',
        summary: 'Создание отклика',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_job_data')
        ),
        tags: ['Jobs'],
        responses: [
            new Response(
                response: 200,
                description: 'Вакансия',
                content: new JsonContent(ref: '#/components/schemas/job_data')
            )
        ]
    )]
    public function applyVacancy(CreateVacancyApplyData $jobData): SuccessData
    {
        $this->vacancyModuleClient->apply($jobData);
        return new SuccessData();
    }

    #[Get(
        path: '/api/v1/vacancies/vacancy/{slug}',
        summary: 'Получить вакансию по slug',
        tags: ['Jobs'],
        parameters: [
            new PathParameter(
                name: 'slug',
                schema: new Schema(type: 'string')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Вакансия',
                content: new JsonContent(ref: '#/components/schemas/job_data')
            )
        ]
    )]
    public function slug(string $slug): JobData
    {
        return $this->vacancyModuleClient->getJobBySlug($slug);
    }
}
