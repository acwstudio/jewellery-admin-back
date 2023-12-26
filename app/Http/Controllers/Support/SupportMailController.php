<?php

declare(strict_types=1);

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\ProductOffer\Size\NoSizeOfferData;
use App\Packages\ModuleClients\MessageModuleClientInterface;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

class SupportMailController extends Controller
{
    public function __construct(
        private readonly MessageModuleClientInterface $messageModuleClient
    ) {
    }
    #[Post(
        path: '/api/v1/supports/no-size',
        summary: 'Отправка письма в поддержку "Нет моего размера"',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/no_size_offer_data')
        ),
        tags: ['Supports'],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function sendMailNoSize(NoSizeOfferData $noMySizeOfferData)
    {
        $this->messageModuleClient->sendMailNoSize($noMySizeOfferData);
        return \response('');
    }
}
