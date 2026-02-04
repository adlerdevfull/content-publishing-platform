<?php
declare(strict_types=1);
namespace App\Providers;
use Domain\Category\Repositories\CategoryRepositoryInterface;
use Domain\Content\Repositories\ContentRepositoryInterface;
use Domain\Media\Repositories\MediaRepositoryInterface;
use Infrastructure\Persistence\Repositories\{EloquentCategoryRepository, EloquentContentRepository, EloquentMediaRepository};
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContentRepositoryInterface::class, EloquentContentRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, EloquentMediaRepository::class);
    }
}
