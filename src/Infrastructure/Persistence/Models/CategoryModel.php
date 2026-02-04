<?php
declare(strict_types=1);
namespace Infrastructure\Persistence\Models;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name','slug','parent_id','description'];
}
