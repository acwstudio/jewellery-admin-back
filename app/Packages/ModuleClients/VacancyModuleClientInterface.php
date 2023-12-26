<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Modules\Vacancies\Models\Job;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Vacancies\CreateDepartmentData;
use App\Packages\DataObjects\Vacancies\CreateJobData;
use App\Packages\DataObjects\Vacancies\CreateVacancyApplyData;
use App\Packages\DataObjects\Vacancies\DepartmentData;
use App\Packages\DataObjects\Vacancies\JobData;
use App\Packages\DataObjects\Vacancies\UpdateDepartmentData;
use App\Packages\DataObjects\Vacancies\UpdateJobData;
use Illuminate\Support\Collection;

interface VacancyModuleClientInterface
{
    public function getAllDepartments(): Collection;

    public function getDepartmentById(int $departmentId): DepartmentData;


    public function createDepartment(CreateDepartmentData $departmentData): DepartmentData;

    public function updateDepartment(int $id, UpdateDepartmentData $departmentData): DepartmentData;

    public function deleteDepartmentById(int $id): SuccessData;

    public function getAllJobs(): Collection;

    public function getJobById(int $jobId): JobData;

    public function getJobBySlug(string $slug): JobData;

    public function createJob(CreateJobData $jobData): JobData;

    public function updateJob(int $id, UpdateJobData $jobData): JobData;

    public function deleteJobById(int $id): SuccessData;

    public function apply(CreateVacancyApplyData $applyData): void;
}
