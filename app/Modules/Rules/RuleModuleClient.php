<?php

declare(strict_types=1);

namespace App\Modules\Rules;

use App\Modules\Rules\Models\Rule;
use App\Modules\Rules\Services\RuleService;
use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Rules\RuleData;
use App\Packages\ModuleClients\RuleModuleClientInterface;
use Illuminate\Support\Collection;
use App\Packages\DataObjects\Rules\CreateRuleData;
use App\Packages\DataObjects\Rules\UpdateRuleData;

final class RuleModuleClient implements RuleModuleClientInterface
{
    public function __construct(
        private readonly RuleService $ruleService
    ) {
    }

    public function getAllRules(): Collection
    {
        return $this->ruleService->getAll()->map(
        /**
 * @phpstan-ignore-next-line
*/
            fn (Rule $rule) => RuleData::fromModel($rule)
        );
    }

    public function getRuleById(int $ruleId): RuleData
    {
        return RuleData::fromModel($this->ruleService->getById($ruleId, true));
    }

    public function createRule(CreateRuleData $ruleData): RuleData
    {
        return RuleData::fromModel(
            $this->ruleService->create(
                $ruleData->title,
                $ruleData->description,
                $ruleData->country,
                $ruleData->date_start,
                $ruleData->date_finish,
                $ruleData->slug
            )
        );
    }

    public function updateRule(int $id, UpdateRuleData $ruleData): RuleData
    {
        return RuleData::fromModel(
            $this->ruleService->update(
                $id,
                $ruleData->title,
                $ruleData->description,
                $ruleData->country,
                $ruleData->date_start,
                $ruleData->date_finish,
                $ruleData->slug
            )
        );
    }

    public function deleteRuleById(int $id): SuccessData
    {
        $this->ruleService->delete($id);
        return new SuccessData();
    }

    public function getRuleBySlug(string $slug): RuleData
    {
        return RuleData::fromModel($this->ruleService->getBySlug($slug, true));
    }
}
