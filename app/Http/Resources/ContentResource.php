<?php
declare(strict_types=1);
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id, 'title' => $this->resource->title,
            'body' => $this->resource->body, 'slug' => $this->resource->slug,
            'status' => $this->resource->status->value, 'visibility' => $this->resource->visibility->value,
            'keywords' => $this->resource->keywords, 'translations' => $this->resource->translations,
            'category_id' => $this->resource->categoryId, 'tag_ids' => $this->resource->tagIds,
            'version' => $this->resource->version, 'author_id' => $this->resource->authorId,
            'publish_at' => $this->resource->publishAt?->format('Y-m-d\TH:i:s'),
        ];
    }
}
