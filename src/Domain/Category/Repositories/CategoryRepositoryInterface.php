<?php

declare(strict_types=1);

namespace Domain\Category\Repositories;

use Domain\Category\Entities\Category;

interface CategoryRepositoryInterface
{
    public function findById(int $id): ?Category;
    /** @return Category[] */
    public function all(): array;
    public function save(Category $category): Category;
    public function delete(int $id): void;
}
