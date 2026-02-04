<?php
declare(strict_types=1);
namespace Infrastructure\Persistence\Models;
use Domain\Content\Enums\{ContentStatus, Visibility};
use Illuminate\Database\Eloquent\Model;

class ContentModel extends Model
{
    protected $table = 'contents';
    protected $fillable = ['author_id','title','body','status','visibility','keywords','translations','category_id','tag_ids','slug','version','locked_by','publish_at'];
    protected function casts(): array { return ['status' => ContentStatus::class, 'visibility' => Visibility::class, 'keywords' => 'array', 'translations' => 'array', 'tag_ids' => 'array', 'publish_at' => 'immutable_datetime']; }
}
