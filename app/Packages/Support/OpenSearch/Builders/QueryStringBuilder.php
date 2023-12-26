<?php

declare(strict_types=1);

namespace App\Packages\Support\OpenSearch\Builders;

use OpenSearch\ScoutDriverPlus\Builders\AbstractParameterizedQueryBuilder;
use OpenSearch\ScoutDriverPlus\QueryParameters\ParameterCollection;
use OpenSearch\ScoutDriverPlus\QueryParameters\Shared\QueryStringParameter;
use OpenSearch\ScoutDriverPlus\QueryParameters\Transformers\FlatArrayTransformer;
use OpenSearch\ScoutDriverPlus\QueryParameters\Validators\AllOfValidator;

final class QueryStringBuilder extends AbstractParameterizedQueryBuilder
{
    use QueryStringParameter;

    protected string $type = 'query_string';

    public function __construct()
    {
        $this->parameters = new ParameterCollection();
        $this->parameterValidator = new AllOfValidator(['query']);
        $this->parameterTransformer = new FlatArrayTransformer();
    }
}
