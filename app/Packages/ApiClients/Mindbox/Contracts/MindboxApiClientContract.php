<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Contracts;

use App\Packages\ApiClients\Mindbox\Requests\ClientSurvey\CreateClientSurveyData;
use App\Packages\ApiClients\Mindbox\Requests\ClientSurvey\PublishedClientSurveyData;
use App\Packages\ApiClients\Mindbox\Requests\WebsiteSetWithList\CreateWebsiteSetWithListData;
use Spatie\LaravelData\Data;

interface MindboxApiClientContract
{
    public function send(string $url, Data $data): void;
    public function clientSurvey(CreateClientSurveyData $data): void;
    public function sendClientSurvey(PublishedClientSurveyData $data): void;
    public function websiteSetWithList(CreateWebsiteSetWithListData $data): void;
}
