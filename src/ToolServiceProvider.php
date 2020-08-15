<?php

namespace Armincms\Blogger;
 
use Illuminate\Support\ServiceProvider; 
use Illuminate\Support\Collection; 
use CodencoDev\NovaGridSystem\NovaGridSystem;
use Laravel\Nova\Nova as LaravelNova; 

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        LaravelNova::serving([$this, 'servingNova']);
    }

    public function servingNova()
    {
        LaravelNova::resources([
            Nova\Post::class,
            Nova\Video::class,
            Nova\Podcast::class,
            Nova\Article::class,
            Nova\Category::class,
            Nova\Tag::class,
        ]); 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    { 
        \Site::push('blog', function($blog) {
            $blog->directory('blog');

            $blog->pushComponent(new Components\Tag);
            $blog->pushComponent(new Components\Post);
            $blog->pushComponent(new Components\Video);
            $blog->pushComponent(new Components\Article);
            $blog->pushComponent(new Components\Podcast);
            $blog->pushComponent(new Components\Category);
        });

        Collection::macro('filterForDetail', function($request, $resource) {
            return $this->filter(function ($field) use ($resource, $request) {
                return $field->isShownOnDetail($request, $resource);
            })->values();
        });
    }
}
