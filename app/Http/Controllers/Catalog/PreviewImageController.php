<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageGetListData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageListData;
use App\Packages\DataObjects\Catalog\PreviewImage\PreviewImageUploadData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class PreviewImageController extends Controller
{
    public function __construct(
        protected readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/catalog/preview_image',
        summary: 'Получить список превью изображений',
        tags: ['Catalog'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Коллекция превью изображений',
                content: new JsonContent(ref: '#/components/schemas/catalog_preview_image_list_data')
            )
        ]
    )]
    public function getList(PreviewImageGetListData $data): PreviewImageListData
    {
        return $this->catalogModuleClient->getPreviewImageList($data);
    }

    #[Post(
        path: '/api/v1/catalog/preview_image',
        summary: 'Загрузить превью изображения',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/catalog_preview_image_upload_data')
        ),
        tags: ['Catalog'],
        responses: [
            new Response(
                response: 200,
                description: 'Превью изображения',
                content: new JsonContent(ref: '#/components/schemas/catalog_preview_image_data')
            )
        ]
    )]
    public function upload(PreviewImageUploadData $data): PreviewImageData
    {
        return $this->catalogModuleClient->createPreviewImage($data);
    }

    #[Delete(
        path: '/api/v1/catalog/preview_image/{id}',
        summary: 'Удалить превью изображения по ID',
        tags: ['Catalog'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(response: 200, description: 'AOK')
        ]
    )]
    public function delete(int $id): \Illuminate\Http\Response
    {
        $this->catalogModuleClient->deletePreviewImage($id);
        return \response('');
    }
}
