<?php
declare(strict_types=1);
namespace Infrastructure\Persistence\Models;
use Illuminate\Database\Eloquent\Model;

class ContentVersionModel extends Model
{
    protected $table = 'content_versions';
    protected $fillable = ['content_id','version','title','body','keywords','translations','edited_by','comment'];
    protected function casts(): array { return ['keywords' => 'array', 'translations' => 'array']; }
}
