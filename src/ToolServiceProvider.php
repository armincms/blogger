<?php

namespace Armincms\Blogger;
 
use Illuminate\Support\ServiceProvider; 
use Illuminate\Support\Collection; 
use Illuminate\Support\Str; 
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
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
        $this->configureWebComponents();
        $this->configureModules();
        $this->configurePolicy();

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

    public function configureWebComponents()
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
    }

    public function configureModules()
    {    
        \Config::set('module.locatables.blog', [
            'title' => 'blog', 
            'name'  => 'blog',
            'items' => collect([
                Nova\Post::class, Nova\Video::class, Nova\Podcast::class, Nova\Article::class
            ])->map(function($resource) {
                return [
                    'title' => $resource::label(),
                    'name' => Str::singular($resource::uriKey()),
                    'id' => '*',
                    'childrens' => $resource::newModel()->resource($resource)->get()->mapInto($resource)->map(function($resource) { 
                        return [
                            'title' => $resource->title() ?? $resource->getKey(), 
                            'name' => Str::singular($resource::uriKey()),
                            'id' => $resource->getKey(),
                            'url'    => $resource->url(),
                        ];
                    })->toArray(),
                ];
            })->values()->toArray()
        ]);   
    }

    public function configurePolicy()
    { 
        \Gate::policy(Blog::class, Policies\Blog::class);
    }

}
