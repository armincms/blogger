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
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
        $this->configureWebComponents();
        $this->configureModules();
        $this->configurePolicy();
        $this->configureMenus();
        $this->servingNova();

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
        ]); 
    }

    public function configureWebComponents()
    { 
        \Site::push('blog', function($blog) {
            $blog->directory('blog');
 
            $blog->pushComponent(new Components\Category); 
            $blog->pushComponent(new Components\Post);
            $blog->pushComponent(new Components\Video);
            $blog->pushComponent(new Components\Article);
            $blog->pushComponent(new Components\Podcast); 
        });
    }

    public function configureModules()
    {    
        \Config::set('module.locatables.blog', [
            'title' => 'blog', 
            'name'  => 'blog',
            'items' => [Locate::class, 'moduleLocales']
        ]);   
    }

    public function configureMenus()
    {     
        \Config::set('menu.menuables.blog', [
            'title' => 'Category',
            'callback' => [Locate::class, 'categoryLocates'],
        ]);   
    }

    public function configurePolicy()
    { 
        \Gate::policy(Blog::class, Policies\Blog::class);
    }

}
