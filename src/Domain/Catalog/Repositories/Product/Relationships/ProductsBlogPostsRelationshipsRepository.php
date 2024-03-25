<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Product\Relationships;

use Domain\AbstractRelationshipsRepository;
use Domain\Catalog\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class ProductsBlogPostsRelationshipsRepository extends AbstractRelationshipsRepository
{

    public function index(array $params): LengthAwarePaginator|Model
    {
        $id = data_get($params, 'id');
        $perPage = data_get($params, 'per_page');
        unset($params['id']);

        return Product::findOrFail($id)->blogPosts()->select()
            ->addSelect(DB::raw('(SELECT name FROM blog_categories as bc
            where blog_posts.blog_category_id = bc.id) as blog_category_name'))
            ->paginate($perPage)->appends($params);
    }

    public function update(array $data): void
    {
        $ids = data_get($data, 'data.*.id');

        Product::findOrFail(data_get($data, 'id'))->blogPosts()->sync($ids);
    }
}
