<?php

declare(strict_types=1);

namespace App\Modules\Vacancies\Repositories;

use App\Modules\Vacancies\Models\Department;
use App\Modules\Vacancies\Models\Job;
use Illuminate\Database\Eloquent\Collection;

class JobRepository
{
    public function getAll(): Collection
    {
        return Job::all();
    }

    public function getById(int $id, bool $fail = false): ?Job
    {
        /**
         * @var Job $job
         */
        $job = Job::query()->where('id', $id);

        if ($fail) {
            /**
             * @phpstan-ignore-next-line
             */
            $job->firstOrFail();
        }
        /**
         * @phpstan-ignore-next-line
         */
        return $job->first();
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
        $job = new Job();

        $job->title = $title;
        $job->salary = $salary;
        $job->city = $city;
        $job->experience = $experience;
        $job->description = $description;
        $job->slug = $slug;

        $job->department()->associate($department);

        $job->save();
        return $job->refresh();
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
        /**
 * @var Job $job
*/
        $job = Job::query()->find($id);

        $job->title = $title;
        $job->salary = $salary;
        $job->city = $city;
        $job->experience = $experience;
        $job->description = $description;
        $job->slug = $slug;

        $job->department()->associate($department);
        $job->save();
        return $job->refresh();
    }

    public function delete(int $id): void
    {
        Job::query()->findOrFail($id)->delete();
    }

    public function getBySlug(string $slug, bool $fail = false): ?Job
    {
        /**
         * @var Job $job
         */
        $job = Job::query()->where('slug', $slug);

        if ($fail) {
            /**
             * @phpstan-ignore-next-line
             */
            $job->firstOrFail();
        }
        /**
         * @phpstan-ignore-next-line
         */
        return $job->first();
    }
}
