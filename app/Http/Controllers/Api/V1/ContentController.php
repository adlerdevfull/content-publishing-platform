<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use Application\Content\Commands\ContentCommandHandler;
use Application\Content\Queries\ContentQueryHandler;
use Domain\Content\Enums\ContentStatus;
use Domain\Content\Repositories\ContentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function __construct(
        private readonly ContentCommandHandler $handler,
        private readonly ContentQueryHandler $queries,
        private readonly ContentRepositoryInterface $contents,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 15);
        $filters = $request->only(['status','category_id','author_id','visibility','from','to']);
        $items = $this->contents->paginate($page, $perPage, $filters);
        $total = $this->contents->count($filters);
        return response()->json(['data' => ContentResource::collection($items), 'meta' => ['total' => $total, 'page' => $page, 'per_page' => $perPage]]);
    }

    public function show(int $id): JsonResponse
    {
        $content = $this->contents->findById($id);
        if (!$content) return response()->json(['error' => 'Content not found'], 404);
        return response()->json(['data' => new ContentResource($content)]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:500', 'body' => 'required|string',
            'visibility' => 'sometimes|string|in:public,restricted,private',
            'keywords' => 'sometimes|array', 'translations' => 'sometimes|array',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'tag_ids' => 'sometimes|array', 'publish_at' => 'sometimes|date',
        ]);
        $content = $this->handler->create($data, auth('api')->id());
        return response()->json(['data' => new ContentResource($content)], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'title' => 'sometimes|string|max:500', 'body' => 'sometimes|string',
            'visibility' => 'sometimes|string|in:public,restricted,private',
            'keywords' => 'sometimes|array', 'translations' => 'sometimes|array',
            'category_id' => 'sometimes|integer', 'tag_ids' => 'sometimes|array',
            'comment' => 'sometimes|string|max:500',
        ]);
        $comment = $data['comment'] ?? null; unset($data['comment']);
        $content = $this->handler->update($id, $data, auth('api')->id(), $comment);
        return response()->json(['data' => new ContentResource($content)]);
    }

    public function transition(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|string|in:draft,in_review,approved,published,archived']);
        $content = $this->handler->transition($id, ContentStatus::from($request->input('status')), auth('api')->id());
        return response()->json(['data' => new ContentResource($content)]);
    }

    public function versions(int $id): JsonResponse
    {
        $versions = $this->contents->getVersions($id);
        return response()->json(['data' => array_map(fn($v) => ['version' => $v->version, 'title' => $v->title, 'edited_by' => $v->editedBy, 'comment' => $v->comment, 'created_at' => $v->createdAt?->format('Y-m-d\TH:i:s')], $versions)]);
    }

    public function restore(Request $request, int $id): JsonResponse
    {
        $request->validate(['version' => 'required|integer']);
        $content = $this->handler->restore($id, $request->input('version'), auth('api')->id());
        return response()->json(['data' => new ContentResource($content)]);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:2']);
        return response()->json(['data' => $this->queries->search($request->input('q'))]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->handler->delete($id);
        return response()->json(null, 204);
    }
}
