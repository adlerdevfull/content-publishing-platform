<?php
declare(strict_types=1);
namespace Infrastructure\Persistence\Repositories;
use Domain\Media\Entities\Media;
use Domain\Media\Repositories\MediaRepositoryInterface;
use Infrastructure\Persistence\Models\MediaModel;

final class EloquentMediaRepository implements MediaRepositoryInterface
{
    public function findById(int $id): ?Media { $m = MediaModel::find($id); return $m ? $this->toDomain($m) : null; }
    public function findByContentId(int $contentId): array { return MediaModel::where('content_id', $contentId)->get()->map(fn($m) => $this->toDomain($m))->all(); }
    public function save(Media $media): Media {
        $m = $media->id ? MediaModel::findOrFail($media->id) : new MediaModel();
        $m->fill(['uploaded_by'=>$media->uploadedBy,'filename'=>$media->filename,'mime_type'=>$media->mimeType,'size_bytes'=>$media->sizeBytes,'path'=>$media->path,'thumbnail_path'=>$media->thumbnailPath,'content_id'=>$media->contentId,'disk'=>$media->disk]);
        $m->save(); $media->id = $m->id; return $media;
    }
    public function delete(int $id): void { MediaModel::destroy($id); }
    private function toDomain(MediaModel $m): Media { return new Media($m->id,$m->uploaded_by,$m->filename,$m->mime_type,$m->size_bytes,$m->path,$m->thumbnail_path,$m->content_id,$m->disk); }
}
