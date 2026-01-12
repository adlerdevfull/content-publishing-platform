<?php

declare(strict_types=1);

namespace Domain\Content\Entities;

final class ContentVersion
{
    public function __construct(
        public ?int $id,
        public int $contentId,
        public int $version,
        public string $title,
        public string $body,
        public array $keywords,
        public array $translations,
        public int $editedBy,
        public ?string $comment = null,
        public ?\DateTimeImmutable $createdAt = null,
    ) {}

    public static function fromContent(Content $content, int $editedBy, ?string $comment = null): self
    {
        return new self(
            id: null,
            contentId: $content->id,
            version: $content->version,
            title: $content->title,
            body: $content->body,
            keywords: $content->keywords,
            translations: $content->translations,
            editedBy: $editedBy,
            comment: $comment,
            createdAt: new \DateTimeImmutable(),
        );
    }
}
