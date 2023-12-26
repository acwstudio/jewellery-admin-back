<?php

declare(strict_types=1);

namespace App\Modules\Vacancies\Repositories;

use App\Modules\Vacancies\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository
{
    public function getAll(): Collection
    {
        return Department::all();
    }

    public function getById(int $id, bool $fail = false): ?Department
    {
        /**
         * @var Department $department
         */
        $department = Department::query()->where('id', $id);

        if ($fail) {
            /**
             * @phpstan-ignore-next-line
             */
            $department->firstOrFail();
        }
        /**
         * @phpstan-ignore-next-line
         */
        return $department->first();
    }

    public function create(string $title): Department
    {
        /**
 * @phpstan-ignore-next-line
*/
        return Department::query()->create(
            [
            'title' => $title
            ]
        );
    }

    public function update(int $id, string $title): Department
    {
        $department = Department::query()->find($id);

        $department->update(['title' => $title]);
        /**
 * @phpstan-ignore-next-line
*/
        return $department->refresh();
    }

    public function delete(int $id): void
    {
        Department::query()->findOrFail($id)->delete();
    }
}
