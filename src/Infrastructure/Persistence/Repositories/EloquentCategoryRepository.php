<?php
declare(strict_types=1);
namespace Infrastructure\Persistence\Repositories;
use Domain\Category\Entities\Category;
use Domain\Category\Repositories\CategoryRepositoryInterface;
use Infrastructure\Persistence\Models\CategoryModel;

final class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function findById(int $id): ?Category { $m = CategoryModel::find($id); return $m ? new Category($m->id,$m->name,$m->slug,$m->parent_id,$m->description) : null; }
    public function all(): array { return CategoryModel::orderBy('name')->get()->map(fn($m) => new Category($m->id,$m->name,$m->slug,$m->parent_id,$m->description))->all(); }
    public function save(Category $c): Category {
        $m = $c->id ? CategoryModel::findOrFail($c->id) : new CategoryModel();
        $m->fill(['name'=>$c->name,'slug'=>$c->slug,'parent_id'=>$c->parentId,'description'=>$c->description]);
        $m->save(); $c->id = $m->id; return $c;
    }
    public function delete(int $id): void { CategoryModel::destroy($id); }
}
