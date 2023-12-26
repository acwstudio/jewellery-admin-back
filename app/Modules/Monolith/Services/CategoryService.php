<?php

namespace App\Modules\Monolith\Services;

use App\Modules\Monolith\Repositories\CategoryRepository;
use Illuminate\Support\LazyCollection;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {
    }

    public function getCategories(): iterable
    {
        return $this->categoryRepository->getCategories();
    }
}
