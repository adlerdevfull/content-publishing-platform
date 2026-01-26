<?php

declare(strict_types=1);

namespace Application\Media\Commands;

use Domain\Media\Entities\Media;
use Domain\Media\Repositories\MediaRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class MediaCommandHandler
{
    public function __construct(
        private MediaRepositoryInterface $medias,
    ) {}

    public function upload(UploadedFile $file, int $userId, ?int $contentId = null): Media
    {
        Media::validate($file->getMimeType(), $file->getSize());

        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $path = $file->storeAs('uploads/' . date('Y/m'), $filename, 'local');

        $thumbnailPath = null;
        if (str_starts_with($file->getMimeType(), 'image/')) {
            $thumbnailPath = $this->generateThumbnail($file, $filename);
        }

        $media = new Media(
            id: null, uploadedBy: $userId, filename: $filename,
            mimeType: $file->getMimeType(), sizeBytes: $file->getSize(),
            path: $path, thumbnailPath: $thumbnailPath, contentId: $contentId,
        );

        return $this->medias->save($media);
    }

    public function delete(int $id): void
    {
        $media = $this->medias->findById($id)
            ?? throw new \DomainException("Media not found");

        Storage::disk('local')->delete($media->path);
        if ($media->thumbnailPath) {
            Storage::disk('local')->delete($media->thumbnailPath);
        }

        $this->medias->delete($id);
    }

    private function generateThumbnail(UploadedFile $file, string $filename): string
    {
        $thumbPath = 'thumbnails/' . date('Y/m') . '/thumb_' . $filename;
        // Simulated thumbnail generation (in production: Intervention Image)
        Storage::disk('local')->put($thumbPath, file_get_contents($file->getRealPath()));
        return $thumbPath;
    }
}
