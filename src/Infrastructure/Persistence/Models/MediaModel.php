<?php
declare(strict_types=1);
namespace Infrastructure\Persistence\Models;
use Illuminate\Database\Eloquent\Model;

class MediaModel extends Model
{
    protected $table = 'media';
    protected $fillable = ['uploaded_by','filename','mime_type','size_bytes','path','thumbnail_path','content_id','disk'];
}
