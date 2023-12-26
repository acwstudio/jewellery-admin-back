<?php

declare(strict_types=1);

namespace App\Modules\Rules\Repositories;

use App\Modules\Rules\Models\Rule;
use Illuminate\Database\Eloquent\Collection;

class RuleRepository
{
    public function getAll(): Collection
    {
        return Rule::all();
    }

    public function getById(int $id, bool $fail = false): ?Rule
    {
        /**
 * @var Rule $rule
*/
        $rule = Rule::query()->where('id', $id);

        if ($fail) {
            /**
 * @phpstan-ignore-next-line
*/
            $rule->firstOrFail();
        }
        /**
 * @phpstan-ignore-next-line
*/
        return $rule->first();
    }

    public function create(
        string $title,
        string $description,
        string $country,
        string $date_start,
        string $date_finish,
        string $slug
    ): Rule {
        /**
         * @phpstan-ignore-next-line
         */
        return Rule::query()->create(
            [
                'title' => $title,
                'description' => $description,
                'country' => $country,
                'date_start' => $date_start,
                'date_finish' => $date_finish,
                'slug' => $slug
            ]
        );
    }

    public function update(
        int $id,
        string $title,
        string $description,
        string $country,
        string $date_start,
        string $date_finish,
        string $slug
    ): Rule {
        $rule = Rule::query()->find($id);

        $rule->update(
            [
                'title' => $title,
                'description' => $description,
                'country' => $country,
                'date_start' => $date_start,
                'date_finish' => $date_finish,
                'slug' => $slug
            ]
        );

        /**
         * @phpstan-ignore-next-line
         */
        return $rule->refresh();
    }

    public function delete(int $id): void
    {
        Rule::query()->findOrFail($id)->delete();
    }

    public function getBySlug(string $slug, bool $fail = false): ?Rule
    {
        /**
         * @var Rule $rule
         */
        $rule = Rule::query()->where('slug', $slug);

        if ($fail) {
            /**
             * @phpstan-ignore-next-line
             */
            $rule->firstOrFail();
        }
        /**
         * @phpstan-ignore-next-line
         */
        return $rule->first();
    }
}
