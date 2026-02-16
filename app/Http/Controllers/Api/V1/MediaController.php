<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Application\Media\Commands\MediaCommandHandler;
use Domain\Media\Repositories\MediaRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(private readonly MediaCommandHandler $handler, private readonly MediaRepositoryInterface $medias) {}

    public function upload(Request $request): JsonResponse
    {
        $request->validate(['file' => 'required|file|max:10240', 'content_id' => 'sometimes|integer|exists:contents,id']);
        $media = $this->handler->upload($request->file('file'), auth('api')->id(), $request->input('content_id'));
        return response()->json(['data' => ['id' => $media->id, 'filename' => $media->filename, 'path' => $media->path, 'thumbnail' => $media->thumbnailPath, 'mime_type' => $media->mimeType, 'size_bytes' => $media->sizeBytes]], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $contentId = $request->get('content_id');
        $items = $contentId ? $this->medias->findByContentId((int) $contentId) : [];
        return response()->json(['data' => array_map(fn($m) => ['id' => $m->id, 'filename' => $m->filename, 'path' => $m->path, 'thumbnail' => $m->thumbnailPath, 'mime_type' => $m->mimeType], $items)]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->handler->delete($id);
        return response()->json(null, 204);
    }
}
