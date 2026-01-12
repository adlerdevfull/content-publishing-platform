<?php

declare(strict_types=1);

namespace Domain\Media\Entities;

final class Media
{
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf', 'application/msword'];
    private const MAX_SIZE_BYTES = 10 * 1024 * 1024; // 10MB

    public function __construct(
        public ?int $id,
        public int $uploadedBy,
        public string $filename,
        public string $mimeType,
        public int $sizeBytes,
        public string $path,
        public ?string $thumbnailPath = null,
        public ?int $contentId = null,
        public ?string $disk = 'local',
    ) {}

    public static function validate(string $mimeType, int $sizeBytes): void
    {
        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            throw new \DomainException("File type {$mimeType} is not allowed");
        }
        if ($sizeBytes > self::MAX_SIZE_BYTES) {
            throw new \DomainException("File exceeds maximum size of 10MB");
        }
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mimeType, 'image/');
    }
}
