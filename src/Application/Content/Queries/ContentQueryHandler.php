<?php

declare(strict_types=1);

namespace Application\Content\Queries;

use Domain\Content\Repositories\ContentRepositoryInterface;
use Illuminate\Support\Facades\Cache;

final readonly class ContentQueryHandler
{
    public function __construct(
        private ContentRepositoryInterface $contents,
    ) {}

    public function findPublished(int $id): ?array
    {
        return Cache::remember("content:{$id}", 600, function () use ($id) {
            $content = $this->contents->findById($id);
            if (!$content || $content->status->value !== 'published') return null;
            return [
                'id' => $content->id, 'title' => $content->title, 'body' => $content->body,
                'slug' => $content->slug, 'keywords' => $content->keywords,
                'translations' => $content->translations, 'visibility' => $content->visibility->value,
                'version' => $content->version, 'publish_at' => $content->publishAt?->format('Y-m-d\TH:i:s'),
            ];
        });
    }

    public function search(string $query): array
    {
        return array_map(fn ($c) => [
            'id' => $c->id, 'title' => $c->title, 'slug' => $c->slug, 'status' => $c->status->value,
        ], $this->contents->search($query));
    }
}
