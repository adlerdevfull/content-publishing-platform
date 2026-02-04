<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repositories;

use Domain\Content\Entities\{Content, ContentVersion};
use Domain\Content\Repositories\ContentRepositoryInterface;
use Infrastructure\Persistence\Models\{ContentModel, ContentVersionModel};

final class EloquentContentRepository implements ContentRepositoryInterface
{
    public function findById(int $id): ?Content
    {
        $m = ContentModel::find($id);
        return $m ? $this->toDomain($m) : null;
    }

    public function findBySlug(string $slug): ?Content
    {
        $m = ContentModel::where('slug', $slug)->first();
        return $m ? $this->toDomain($m) : null;
    }

    public function paginate(int $page = 1, int $perPage = 15, array $filters = []): array
    {
        $q = ContentModel::query();
        if (isset($filters['status'])) $q->where('status', $filters['status']);
        if (isset($filters['category_id'])) $q->where('category_id', $filters['category_id']);
        if (isset($filters['author_id'])) $q->where('author_id', $filters['author_id']);
        if (isset($filters['visibility'])) $q->where('visibility', $filters['visibility']);
        if (isset($filters['from'])) $q->where('created_at', '>=', $filters['from']);
        if (isset($filters['to'])) $q->where('created_at', '<=', $filters['to']);

        return $q->offset(($page - 1) * $perPage)->limit($perPage)
            ->orderBy('created_at', 'desc')->get()
            ->map(fn ($m) => $this->toDomain($m))->all();
    }

    public function count(array $filters = []): int
    {
        $q = ContentModel::query();
        if (isset($filters['status'])) $q->where('status', $filters['status']);
        if (isset($filters['category_id'])) $q->where('category_id', $filters['category_id']);
        return $q->count();
    }

    public function save(Content $content): Content
    {
        $m = $content->id ? ContentModel::findOrFail($content->id) : new ContentModel();
        $m->fill([
            'author_id' => $content->authorId, 'title' => $content->title, 'body' => $content->body,
            'status' => $content->status->value, 'visibility' => $content->visibility->value,
            'keywords' => $content->keywords, 'translations' => $content->translations,
            'category_id' => $content->categoryId, 'tag_ids' => $content->tagIds,
            'slug' => $content->slug, 'version' => $content->version,
            'locked_by' => $content->lockedBy, 'publish_at' => $content->publishAt,
        ]);
        $m->save();
        $content->id = $m->id;
        return $content;
    }

    public function delete(int $id): void { ContentModel::destroy($id); }

    public function saveVersion(ContentVersion $v): ContentVersion
    {
        $m = ContentVersionModel::create([
            'content_id' => $v->contentId, 'version' => $v->version,
            'title' => $v->title, 'body' => $v->body,
            'keywords' => $v->keywords, 'translations' => $v->translations,
            'edited_by' => $v->editedBy, 'comment' => $v->comment,
        ]);
        $v->id = $m->id;
        return $v;
    }

    public function getVersions(int $contentId): array
    {
        return ContentVersionModel::where('content_id', $contentId)
            ->orderBy('version', 'desc')->get()
            ->map(fn ($m) => new ContentVersion(
                $m->id, $m->content_id, $m->version, $m->title, $m->body,
                $m->keywords ?? [], $m->translations ?? [], $m->edited_by, $m->comment,
                $m->created_at ? new \DateTimeImmutable($m->created_at->toDateTimeString()) : null,
            ))->all();
    }

    public function getVersion(int $contentId, int $version): ?ContentVersion
    {
        $m = ContentVersionModel::where('content_id', $contentId)->where('version', $version)->first();
        if (!$m) return null;
        return new ContentVersion($m->id, $m->content_id, $m->version, $m->title, $m->body, $m->keywords ?? [], $m->translations ?? [], $m->edited_by, $m->comment);
    }

    public function search(string $query, int $limit = 20): array
    {
        return ContentModel::where('title', 'ilike', "%{$query}%")
            ->orWhere('body', 'ilike', "%{$query}%")
            ->limit($limit)->get()
            ->map(fn ($m) => $this->toDomain($m))->all();
    }

    private function toDomain(ContentModel $m): Content
    {
        return new Content(
            id: $m->id, authorId: $m->author_id, title: $m->title, body: $m->body,
            status: $m->status, visibility: $m->visibility,
            keywords: $m->keywords ?? [], translations: $m->translations ?? [],
            categoryId: $m->category_id, tagIds: $m->tag_ids ?? [],
            slug: $m->slug, version: $m->version, lockedBy: $m->locked_by,
            publishAt: $m->publish_at, createdAt: $m->created_at ? new \DateTimeImmutable($m->created_at->toDateTimeString()) : null,
        );
    }
}
