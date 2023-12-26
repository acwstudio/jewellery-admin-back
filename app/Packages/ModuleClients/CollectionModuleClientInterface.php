<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Modules\Collections\Support\Filters\CollectionFilter;
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

interface CollectionModuleClientInterface
{
    public function getCollection(int $id): ?CollectionData;

    public function getCollectionShort(int $id): ?CollectionShortData;

    public function getCollectionBySlug(string $slug): ?CollectionData;

    public function getCollections(GetListCollectionData $data): CollectionListData;

    public function getCollectionProductListItems(?PaginationData $data = null): CollectionProductListItemListData;

    public function getCollectionProducts(GetListCollectionProductData $data): ?ProductListData;

    public function importCollectionProducts(?callable $onEach = null): void;

    public function exportCollections(CollectionFilter $filter): void;

    public function importCollections(?callable $onEach = null): void;

    public function createCollection(CreateCollectionData $data): CollectionData;

    public function updateCollection(UpdateCollectionData $data): CollectionData;

    public function deleteCollection(int $id): void;

    public function getFavorite(int $id): ?FavoriteData;

    public function getFavoriteBySlug(string $slug): ?FavoriteData;

    public function getFavorites(GetListFavoriteData $data): FavoriteListData;

    public function createFavorite(CreateFavoriteData $data): FavoriteData;

    public function updateFavorite(UpdateFavoriteData $data): FavoriteData;

    public function deleteFavorite(int $id): void;

    public function getFiles(GetListFileData $data): FileListData;

    public function createFile(CreateFileData $data): FileData;

    public function deleteFile(int $id): void;

    public function getStones(GetListStoneData $data): StoneListData;

    public function createStone(CreateStoneData $data): StoneData;

    public function deleteStone(int $id): void;
}
