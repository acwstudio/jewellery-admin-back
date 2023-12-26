<?php

declare(strict_types=1);

namespace App\Modules\Collections;

use App\Modules\Collections\Services\CollectionProductListItemService;
use App\Modules\Collections\Services\CollectionService;
use App\Modules\Collections\Services\FavoriteService;
use App\Modules\Collections\Services\FileService;
use App\Modules\Collections\Services\StoneService;
use App\Modules\Collections\Support\Blueprints\FavoriteBlueprint;
use App\Modules\Collections\Support\Filters\CollectionFilter;
use App\Modules\Collections\Support\Pagination;
use App\Modules\Collections\UseCases\CreateOrUpdateCollection;
use App\Modules\Collections\UseCases\ExportCollections;
use App\Modules\Collections\UseCases\GetProducts;
use App\Modules\Collections\UseCases\ImportCollectionProducts;
use App\Modules\Collections\UseCases\ImportCollections;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Collections\Collection\CollectionData;
use App\Packages\DataObjects\Collections\Collection\CollectionListData;
use App\Packages\DataObjects\Collections\Collection\CollectionShortData;
use App\Packages\DataObjects\Collections\Collection\CreateCollectionData;
use App\Packages\DataObjects\Collections\Collection\GetListCollectionData;
use App\Packages\DataObjects\Collections\Collection\UpdateCollectionData;
use App\Packages\DataObjects\Collections\CollectionProduct\CollectionProductListItemListData;
use App\Packages\DataObjects\Collections\CollectionProduct\GetListCollectionProductData;
use App\Packages\DataObjects\Collections\Favorite\CreateFavoriteData;
use App\Packages\DataObjects\Collections\Favorite\FavoriteData;
use App\Packages\DataObjects\Collections\Favorite\FavoriteListData;
use App\Packages\DataObjects\Collections\Favorite\GetListFavoriteData;
use App\Packages\DataObjects\Collections\Favorite\UpdateFavoriteData;
use App\Packages\DataObjects\Collections\File\CreateFileData;
use App\Packages\DataObjects\Collections\File\FileListData;
use App\Packages\DataObjects\Collections\File\GetListFileData;
use App\Packages\DataObjects\Collections\File\FileData;
use App\Packages\DataObjects\Collections\Stone\CreateStoneData;
use App\Packages\DataObjects\Collections\Stone\GetListStoneData;
use App\Packages\DataObjects\Collections\Stone\StoneData;
use App\Packages\DataObjects\Collections\Stone\StoneListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

final class CollectionModuleClient implements CollectionModuleClientInterface
{
    public function __construct(
        private readonly CollectionService $collectionService,
        private readonly FavoriteService $favoriteService,
        private readonly FileService $fileService,
        private readonly StoneService $stoneService,
        private readonly CollectionProductListItemService $collectionProductListItemService,
    ) {
    }

    public function getCollection(int $id): ?CollectionData
    {
        $collection = $this->collectionService->getCollection($id);

        if (is_null($collection)) {
            return null;
        }

        return CollectionData::fromModel($collection);
    }

    public function getCollectionShort(int $id): ?CollectionShortData
    {
        $collection = $this->collectionService->getCollection($id);

        if (is_null($collection)) {
            return null;
        }

        return CollectionShortData::fromModel($collection);
    }

    public function getCollectionBySlug(string $slug): ?CollectionData
    {
        $collection = $this->collectionService->getCollectionBySlug($slug);

        if (is_null($collection)) {
            return null;
        }

        return CollectionData::fromModel($collection);
    }

    public function getCollections(GetListCollectionData $data): CollectionListData
    {
        $paginator = $this->collectionService->getCollections(
            new Pagination(
                $data->pagination?->page,
                $data->pagination?->per_page
            )
        );

        return CollectionListData::fromPaginator($paginator);
    }

    public function getCollectionProductListItems(?PaginationData $data = null): CollectionProductListItemListData
    {
        $paginator = $this->collectionProductListItemService->getList(
            new Pagination(
                $data?->page,
                $data?->per_page
            )
        );

        return CollectionProductListItemListData::fromPaginator($paginator);
    }

    public function getCollectionProducts(GetListCollectionProductData $data): ?ProductListData
    {
        $collection = $this->collectionService->getCollection($data->id);
        if (is_null($collection) || $collection->products()->allRelatedIds()->count() === 0) {
            return null;
        }

        $productGetListData = new ProductGetListData(
            sort_by: $data->sort_by,
            sort_order: $data->sort_order,
            pagination: $data->pagination,
            filter: new FilterProductData(
                price: $data->filter?->price,
                size: $data->filter?->size,
                in_stock: true,
                category: $data->filter?->category,
                brands: $data->filter?->brands,
                ids: $data->filter?->ids ?? $collection->products()->allRelatedIds()->all(),
                feature: $data->filter?->feature,
                has_image: true,
                is_active: true,
            )
        );

        /** @var ProductListData $productListData */
        $productListData = App::call(GetProducts::class, ['data' => $productGetListData]);
        return $productListData;
    }

    public function importCollectionProducts(?callable $onEach = null): void
    {
        App::call(ImportCollectionProducts::class);
    }

    public function exportCollections(CollectionFilter $filter): void
    {
        App::call(ExportCollections::class, ['filter' => $filter]);
    }

    public function importCollections(?callable $onEach = null): void
    {
        App::call(ImportCollections::class);
    }

    public function createCollection(CreateCollectionData $data): CollectionData
    {
        /** @var CollectionData $collectionData */
        $collectionData = App::call(CreateOrUpdateCollection::class, ['data' => $data]);
        return $collectionData;
    }

    public function updateCollection(UpdateCollectionData $data): CollectionData
    {
        /** @var CollectionData $collectionData */
        $collectionData = App::call(CreateOrUpdateCollection::class, ['data' => $data]);
        return $collectionData;
    }

    public function deleteCollection(int $id): void
    {
        $this->collectionService->deleteCollection($id);
    }

    public function getFavorite(int $id): ?FavoriteData
    {
        $favorite = $this->favoriteService->getFavorite($id);

        if (is_null($favorite)) {
            return null;
        }

        return FavoriteData::fromModel($favorite);
    }

    public function getFavoriteBySlug(string $slug): ?FavoriteData
    {
        $favorite = $this->favoriteService->getFavoriteBySlug($slug);

        if (is_null($favorite)) {
            return null;
        }

        return FavoriteData::fromModel($favorite);
    }

    public function getFavorites(GetListFavoriteData $data): FavoriteListData
    {
        /** По условию вывода избранных коллекций - не более 4 */
        $paginator = $this->favoriteService->getFavorites(
            new Pagination(1, 4)
        );

        return FavoriteListData::fromPaginator($paginator);
    }

    public function createFavorite(CreateFavoriteData $data): FavoriteData
    {
        $favoriteBlueprint = new FavoriteBlueprint(
            Str::slug($data->slug),
            $data->name,
            $data->description,
            $data->background_color,
            $data->font_color
        );

        $favorite = $this->favoriteService->createFavorite(
            $favoriteBlueprint,
            $data->collection_id,
            $data->image_id,
            $data->image_mob_id
        );

        return FavoriteData::fromModel($favorite);
    }

    public function updateFavorite(UpdateFavoriteData $data): FavoriteData
    {
        $favoriteBlueprint = new FavoriteBlueprint(
            Str::slug($data->slug),
            $data->name,
            $data->description,
            $data->background_color,
            $data->font_color
        );

        $favorite = $this->favoriteService->updateFavorite(
            $data->id,
            $favoriteBlueprint,
            $data->collection_id,
            $data->image_id,
            $data->image_mob_id
        );

        return FavoriteData::fromModel($favorite);
    }

    public function deleteFavorite(int $id): void
    {
        $this->favoriteService->deleteFavorite($id);
    }

    public function getFiles(GetListFileData $data): FileListData
    {
        $paginator = $this->fileService->getFiles(
            new Pagination(
                $data->pagination?->page,
                $data->pagination?->per_page
            )
        );

        return FileListData::fromPaginator($paginator);
    }

    public function createFile(CreateFileData $data): FileData
    {
        $file = $this->fileService->createFile($data->file);

        return FileData::fromModel($file);
    }

    public function deleteFile(int $id): void
    {
        $this->fileService->deleteFile($id);
    }

    public function getStones(GetListStoneData $data): StoneListData
    {
        $paginator = $this->stoneService->getStones(
            new Pagination(
                $data->pagination?->page,
                $data->pagination?->per_page
            )
        );

        return StoneListData::fromPaginator($paginator);
    }

    public function createStone(CreateStoneData $data): StoneData
    {
        $stone = $this->stoneService->createStone($data->name);

        return StoneData::fromModel($stone);
    }

    public function deleteStone(int $id): void
    {
        $this->stoneService->deleteStone($id);
    }
}
