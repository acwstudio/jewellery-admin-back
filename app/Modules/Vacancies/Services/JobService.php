<?php

declare(strict_types=1);

namespace App\Modules\Vacancies\Services;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Models\Job;
use App\Modules\Vacancies\Repositories\JobRepository;
use Illuminate\Database\Eloquent\Collection;

class JobService
{
    public function __construct(
        private readonly JobRepository $jobRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->jobRepository->getAll();
    }

    public function getById(int $id, bool $fail = false): ?Job
    {
        return $this->jobRepository->getById($id, $fail);
    }

    public function create(
        Department $department,
        string $title,
        string $salary,
        string $city,
        string $experience,
        string $description,
        string $slug
    ): Job {
        return $this->jobRepository->create(
            $department,
            $title,
            $salary,
            $city,
            $experience,
            $description,
            $slug
        );
    }

    public function update(
        int $id,
        Department $department,
        string $title,
        string $salary,
        string $city,
        string $experience,
        string $description,
        string $slug
    ): Job {
        return $this->jobRepository->update(
            $id,
            $department,
            $title,
            $salary,
            $city,
            $experience,
            $description,
            $slug
        );
    }

    public function delete(int $id): void
    {
        $this->jobRepository->delete($id);
    }

    public function getBySlug(string $slug, bool $fail = false): ?Job
    {
        return $this->jobRepository->getBySlug($slug, $fail);
    }
}
