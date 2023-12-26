<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Common\Response\SuccessData;
use App\Packages\DataObjects\Rules\CreateRuleData;
use App\Packages\DataObjects\Rules\RuleData;
use App\Packages\DataObjects\Rules\UpdateRuleData;
use Illuminate\Support\Collection;

interface RuleModuleClientInterface
{
    public function getAllRules(): Collection;

    public function getRuleById(int $ruleId): RuleData;

    public function createRule(CreateRuleData $ruleData): RuleData;

    public function updateRule(int $id, UpdateRuleData $ruleData): RuleData;

    public function deleteRuleById(int $id): SuccessData;
}
