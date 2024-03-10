<?php


use Domain\Blog\Repositories\BlogCategory\BlogCategoryCachedRepository;
use Domain\Blog\Repositories\BlogCategory\BlogCategoryRepository;
use Domain\Blog\Repositories\BlogCategory\BlogCategoryRepositoryInterface;
use Domain\Blog\Repositories\BlogPost\BlogPostCachedRepository;
use Domain\Blog\Repositories\BlogPost\BlogPostRepository;
use Domain\Blog\Repositories\BlogPost\BlogPostRepositoryInterface;
use Domain\Catalog\Repositories\Price\PriceCachedRepository;
use Domain\Catalog\Repositories\Price\PriceRepository;
use Domain\Catalog\Repositories\Price\PriceRepositoryInterface;
use Domain\Catalog\Repositories\PriceCategory\PriceCategoryCachedRepository;
use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRepository;
use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRepositoryInterface;
use Domain\Catalog\Repositories\Product\ProductCachedRepository;
use Domain\Catalog\Repositories\Product\ProductRepository;
use Domain\Catalog\Repositories\Product\ProductRepositoryInterface;
use Domain\Catalog\Repositories\ProductCategory\ProductCategoryCachedRepository;
use Domain\Catalog\Repositories\ProductCategory\ProductCategoryRepository;
use Domain\Catalog\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Domain\Catalog\Repositories\Size\SizeCachedRepository;
use Domain\Catalog\Repositories\Size\SizeRepository;
use Domain\Catalog\Repositories\Size\SizeRepositoryInterface;
use Domain\Catalog\Repositories\SizeCategory\SizeCategoryCachedRepository;
use Domain\Catalog\Repositories\SizeCategory\SizeCategoryRepository;
use Domain\Catalog\Repositories\SizeCategory\SizeCategoryRepositoryInterface;
use Domain\Catalog\Repositories\Weave\WeaveCachedRepository;
use Domain\Catalog\Repositories\Weave\WeaveRepository;
use Domain\Catalog\Repositories\Weave\WeaveRepositoryInterface;
use Domain\Performance\Repositories\Banner\BannerCachedRepository;
use Domain\Performance\Repositories\Banner\BannerRepository;
use Domain\Performance\Repositories\Banner\BannerRepositoryInterface;
use Domain\Performance\Repositories\ImageBanner\ImageBannerCachedRepository;
use Domain\Performance\Repositories\ImageBanner\ImageBannerRepository;
use Domain\Performance\Repositories\ImageBanner\ImageBannerRepositoryInterface;
use Domain\Performance\Repositories\TypeBanner\TypeBannerCachedRepository;
use Domain\Performance\Repositories\TypeBanner\TypeBannerRepository;
use Domain\Performance\Repositories\TypeBanner\TypeBannerRepositoryInterface;
use Domain\Performance\Repositories\TypeDevice\TypeDeviceCachedRepository;
use Domain\Performance\Repositories\TypeDevice\TypeDeviceRepository;
use Domain\Performance\Repositories\TypeDevice\TypeDeviceRepositoryInterface;
use Domain\Performance\Repositories\TypePage\TypePageCachedRepository;
use Domain\Performance\Repositories\TypePage\TypePageRepository;
use Domain\Performance\Repositories\TypePage\TypePageRepositoryInterface;

return [
    [
        'interface'      => BlogCategoryRepositoryInterface::class,
        'implementation' => BlogCategoryRepository::class,
        'cache'          => BlogCategoryCachedRepository::class
    ],
    [
        'interface'      => BlogPostRepositoryInterface::class,
        'implementation' => BlogPostRepository::class,
        'cache'          => BlogPostCachedRepository::class
    ],
    [
        'interface'      => WeaveRepositoryInterface::class,
        'implementation' => WeaveRepository::class,
        'cache'          => WeaveCachedRepository::class
    ],
    [
        'interface'      => ProductRepositoryInterface::class,
        'implementation' => ProductRepository::class,
        'cache'          => ProductCachedRepository::class
    ],
    [
        'interface'      => ProductCategoryRepositoryInterface::class,
        'implementation' => ProductCategoryRepository::class,
        'cache'          => ProductCategoryCachedRepository::class
    ],
    [
        'interface'      => PriceRepositoryInterface::class,
        'implementation' => PriceRepository::class,
        'cache'          => PriceCachedRepository::class
    ],
    [
        'interface'      => PriceCategoryRepositoryInterface::class,
        'implementation' => PriceCategoryRepository::class,
        'cache'          => PriceCategoryCachedRepository::class
    ],
    [
        'interface'      => BannerRepositoryInterface::class,
        'implementation' => BannerRepository::class,
        'cache'          => BannerCachedRepository::class
    ],
    [
        'interface'      => ImageBannerRepositoryInterface::class,
        'implementation' => ImageBannerRepository::class,
        'cache'          => ImageBannerCachedRepository::class
    ],
    [
        'interface'      => TypeBannerRepositoryInterface::class,
        'implementation' => TypeBannerRepository::class,
        'cache'          => TypeBannerCachedRepository::class
    ],
    [
        'interface'      => TypePageRepositoryInterface::class,
        'implementation' => TypePageRepository::class,
        'cache'          => TypePageCachedRepository::class
    ],
    [
        'interface'      => TypeDeviceRepositoryInterface::class,
        'implementation' => TypeDeviceRepository::class,
        'cache'          => TypeDeviceCachedRepository::class
    ],
    [
        'interface'      => SizeRepositoryInterface::class,
        'implementation' => SizeRepository::class,
        'cache'          => SizeCachedRepository::class
    ],
    [
        'interface'      => SizeCategoryRepositoryInterface::class,
        'implementation' => SizeCategoryRepository::class,
        'cache'          => SizeCategoryCachedRepository::class
    ],
];
