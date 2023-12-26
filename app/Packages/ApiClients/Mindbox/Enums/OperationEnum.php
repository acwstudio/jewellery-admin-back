<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Enums;

enum OperationEnum: string
{
    case CLIENT_SURVEY = 'Client.Survey';
    case WEBSITE_SET_WISH_LIST = 'Website.SetWishList';
}
