<?php

declare(strict_types=1);

namespace Domain\Content\Entities;

use Domain\Content\Enums\ContentStatus;
use Domain\Content\Enums\Visibility;

final class Content
{
    public function __construct(
        public ?int $id,
        public int $authorId,
        public string $title,
        public string $body,
        public ContentStatus $status = ContentStatus::Draft,
        public Visibility $visibility = Visibility::Public,
        public array $keywords = [],
        public array $translations = [], // ['es' => ['title' => ..., 'body' => ...]]
        public ?int $categoryId = null,
        public array $tagIds = [],
        public ?string $slug = null,
        public ?int $version = 1,
        public ?int $lockedBy = null,
        public ?\DateTimeImmutable $publishAt = null,
        public ?\DateTimeImmutable $createdAt = null,
    ) {}

    public function transitionTo(ContentStatus $newStatus): void
    {
        if (!$this->status->canTransitionTo($newStatus)) {
            throw new \DomainException("Cannot transition from {$this->status->value} to {$newStatus->value}");
        }
        $this->status = $newStatus;
    }

    public function lock(int $userId): void
    {
        if ($this->lockedBy !== null && $this->lockedBy !== $userId) {
            throw new \DomainException("Content is locked by another user");
        }
        $this->lockedBy = $userId;
    }

    public function unlock(): void
    {
        $this->lockedBy = null;
    }

    public function isLockedBy(int $userId): bool
    {
        return $this->lockedBy === $userId;
    }

    public function generateSlug(): void
    {
        $this->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title), '-'));
    }

    public function incrementVersion(): void
    {
        $this->version++;
    }
}
