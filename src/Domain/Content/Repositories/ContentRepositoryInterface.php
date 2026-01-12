<?php

declare(strict_types=1);

namespace Domain\Content\Repositories;

use Domain\Content\Entities\{Content, ContentVersion};

interface ContentRepositoryInterface
{
    public function findById(int $id): ?Content;
    public function findBySlug(string $slug): ?Content;
    /** @return Content[] */
    public function paginate(int $page = 1, int $perPage = 15, array $filters = []): array;
    public function count(array $filters = []): int;
    public function save(Content $content): Content;
    public function delete(int $id): void;
    public function saveVersion(ContentVersion $version): ContentVersion;
    /** @return ContentVersion[] */
    public function getVersions(int $contentId): array;
    public function getVersion(int $contentId, int $version): ?ContentVersion;
    /** @return Content[] */
    public function search(string $query, int $limit = 20): array;
}
