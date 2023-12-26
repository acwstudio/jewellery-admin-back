<?php

declare(strict_types=1);

namespace App\Modules\Vacancies\Services;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Repositories\DepartmentRepository;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->departmentRepository->getAll();
    }

    public function getById(int $id, bool $fail = false): ?Department
    {
        return $this->departmentRepository->getById($id, $fail);
    }

    public function create(string $title): Department
    {
        return $this->departmentRepository->create($title);
    }

    public function update(int $id, string $title): Department
    {
        return $this->departmentRepository->update($id, $title);
    }

    public function delete(int $id): void
    {
        $this->departmentRepository->delete($id);
    }
}
