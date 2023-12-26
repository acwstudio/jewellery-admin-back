<?php

declare(strict_types=1);

namespace App\Modules\Rules\Services;

use App\Modules\Rules\Models\Rule;
use App\Modules\Rules\Repositories\RuleRepository;
use Illuminate\Database\Eloquent\Collection;

class RuleService
{
    public function __construct(
        private readonly RuleRepository $ruleRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->ruleRepository->getAll();
    }

    public function getById(int $id, bool $fail = false): ?Rule
    {
        return $this->ruleRepository->getById($id, $fail);
    }

    public function create(
        string $title,
        string $description,
        string $country,
        string $date_start,
        string $date_finish,
        string $slug
    ): Rule {
        return $this->ruleRepository->create(
            $title,
            $description,
            $country,
            $date_start,
            $date_finish,
            $slug
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
        return $this->ruleRepository->update(
            $id,
            $title,
            $description,
            $country,
            $date_start,
            $date_finish,
            $slug
        );
    }

    public function delete(int $id): void
    {
        $this->ruleRepository->delete($id);
    }

    public function getBySlug(string $slug, bool $fail = false): ?Rule
    {
        return $this->ruleRepository->getBySlug($slug, $fail);
    }
}
