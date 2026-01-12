<?php

declare(strict_types=1);

namespace Domain\Category\Entities;

final class Category
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $slug = null,
        public ?int $parentId = null,
        public ?string $description = null,
    ) {}

    public function generateSlug(): void
    {
        $this->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->name), '-'));
    }
}
