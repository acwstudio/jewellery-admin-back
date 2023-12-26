<?php

declare(strict_types=1);

namespace App\Modules\Vacancies;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Models\Job;
use App\Modules\Vacancies\Services\DepartmentService;
use App\Modules\Vacancies\Services\JobService;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Vacancies\CreateDepartmentData;
use App\Packages\DataObjects\Vacancies\CreateJobData;
use App\Packages\DataObjects\Vacancies\CreateVacancyApplyData;
use App\Packages\DataObjects\Vacancies\DepartmentData;
use App\Packages\DataObjects\Vacancies\JobData;
use App\Packages\DataObjects\Vacancies\UpdateDepartmentData;
use App\Packages\DataObjects\Vacancies\UpdateJobData;
use App\Packages\ModuleClients\MessageModuleClientInterface;
use App\Packages\ModuleClients\VacancyModuleClientInterface;
use Illuminate\Support\Collection;

final class VacancyModuleClient implements VacancyModuleClientInterface
{
    public function __construct(
        private readonly DepartmentService $departmentService,
        private readonly MessageModuleClientInterface $messageModuleClient,
        private readonly JobService $jobService
    ) {
    }

    public function getAllDepartments(): Collection
    {
        return $this->departmentService->getAll()->map(
        /**
 * @phpstan-ignore-next-line
*/
            fn (Department $department) => DepartmentData::fromModel($department)
        );
    }

    public function getDepartmentById(int $departmentId): DepartmentData
    {
        return DepartmentData::fromModel($this->departmentService->getById($departmentId, true));
    }

    public function createDepartment(CreateDepartmentData $departmentData): DepartmentData
    {
        return DepartmentData::fromModel($this->departmentService->create($departmentData->title));
    }

    public function updateDepartment(int $id, UpdateDepartmentData $departmentData): DepartmentData
    {
        return DepartmentData::fromModel($this->departmentService->update($id, $departmentData->title));
    }

    public function deleteDepartmentById(int $id): SuccessData
    {
        $this->departmentService->delete($id);
        return new SuccessData();
    }

    public function getAllJobs(): Collection
    {
        return $this->jobService->getAll()->map(
        /**
 * @phpstan-ignore-next-line
*/
            fn (Job $job) => JobData::fromModel($job)
        );
    }

    public function getJobById(int $jobId): JobData
    {
        return JobData::fromModel($this->jobService->getById($jobId, true));
    }

    public function createJob(CreateJobData $jobData): JobData
    {
        $department = $this->departmentService->getById($jobData->department_id, true);
        $job = $this->jobService->create(
            $department,
            $jobData->title,
            $jobData->salary,
            $jobData->city,
            $jobData->experience,
            $jobData->description,
            $jobData->slug
        );
        return JobData::fromModel($job);
    }

    public function updateJob(int $id, UpdateJobData $jobData): JobData
    {
        $department = $this->departmentService->getById($jobData->department_id, true);
        $job = $this->jobService->update(
            $id,
            $department,
            $jobData->title,
            $jobData->salary,
            $jobData->city,
            $jobData->experience,
            $jobData->description,
            $jobData->slug
        );
        return JobData::fromModel($job);
    }

    public function deleteJobById(int $id): SuccessData
    {
        $this->jobService->delete($id);
        return new SuccessData();
    }

    public function apply(CreateVacancyApplyData $applyData): void
    {
        $this->messageModuleClient->applyVacancy($applyData);
    }

    public function getJobBySlug(string $slug): JobData
    {
        return JobData::fromModel($this->jobService->getBySlug($slug, true));
    }
}
