<?php

declare(strict_types=1);

namespace Application\Content\Commands;

use Domain\Content\Entities\{Content, ContentVersion};
use Domain\Content\Enums\{ContentStatus, Visibility};
use Domain\Content\Repositories\ContentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

final readonly class ContentCommandHandler
{
    public function __construct(
        private ContentRepositoryInterface $contents,
    ) {}

    public function create(array $data, int $authorId): Content
    {
        $content = new Content(
            id: null, authorId: $authorId, title: $data['title'], body: $data['body'],
            visibility: Visibility::from($data['visibility'] ?? 'public'),
            keywords: $data['keywords'] ?? [], translations: $data['translations'] ?? [],
            categoryId: $data['category_id'] ?? null, tagIds: $data['tag_ids'] ?? [],
            publishAt: isset($data['publish_at']) ? new \DateTimeImmutable($data['publish_at']) : null,
        );
        $content->generateSlug();
        return $this->contents->save($content);
    }

    public function update(int $id, array $data, int $editorId, ?string $comment = null): Content
    {
        $content = $this->contents->findById($id)
            ?? throw new \DomainException("Content not found");

        // Concurrency control - optimistic locking
        $content->lock($editorId);

        // Save current version before editing
        $version = ContentVersion::fromContent($content, $editorId, $comment);
        $this->contents->saveVersion($version);

        if (isset($data['title'])) { $content->title = $data['title']; $content->generateSlug(); }
        if (isset($data['body'])) $content->body = $data['body'];
        if (isset($data['keywords'])) $content->keywords = $data['keywords'];
        if (isset($data['translations'])) $content->translations = $data['translations'];
        if (isset($data['visibility'])) $content->visibility = Visibility::from($data['visibility']);
        if (isset($data['category_id'])) $content->categoryId = $data['category_id'];
        if (isset($data['tag_ids'])) $content->tagIds = $data['tag_ids'];

        $content->incrementVersion();
        $content->unlock();

        $saved = $this->contents->save($content);
        Cache::forget("content:{$id}");
        return $saved;
    }

    public function transition(int $id, ContentStatus $status, int $userId): Content
    {
        $content = $this->contents->findById($id)
            ?? throw new \DomainException("Content not found");

        $content->transitionTo($status);

        if ($status === ContentStatus::Published) {
            Cache::forget("content:{$id}");
        }

        return $this->contents->save($content);
    }

    public function restore(int $contentId, int $version, int $userId): Content
    {
        $content = $this->contents->findById($contentId)
            ?? throw new \DomainException("Content not found");

        $oldVersion = $this->contents->getVersion($contentId, $version)
            ?? throw new \DomainException("Version not found");

        // Save current as new version
        $this->contents->saveVersion(ContentVersion::fromContent($content, $userId, "Before restore to v{$version}"));

        $content->title = $oldVersion->title;
        $content->body = $oldVersion->body;
        $content->keywords = $oldVersion->keywords;
        $content->translations = $oldVersion->translations;
        $content->incrementVersion();
        $content->generateSlug();

        Cache::forget("content:{$contentId}");
        return $this->contents->save($content);
    }

    public function delete(int $id): void
    {
        $this->contents->delete($id);
        Cache::forget("content:{$id}");
    }
}
