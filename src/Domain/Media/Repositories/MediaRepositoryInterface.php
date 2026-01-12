<?php

declare(strict_types=1);

namespace Domain\Media\Repositories;

use Domain\Media\Entities\Media;

interface MediaRepositoryInterface
{
    public function findById(int $id): ?Media;
    /** @return Media[] */
    public function findByContentId(int $contentId): array;
    public function save(Media $media): Media;
    public function delete(int $id): void;
}
