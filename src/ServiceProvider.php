<?php

namespace Armincms\Blogger;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Laravel\Nova\Nova as LaravelNova;
use Zareismail\Gutenberg\Gutenberg;

class ServiceProvider extends AuthServiceProvider implements DeferrableProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Models\Article::class => Policies\Blog::class,
        Models\Podcast::class => Policies\Blog::class,
        Models\Post::class => Policies\Blog::class,
        Models\Video::class => Policies\Blog::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->registerPolicies();
        $this->conversions();
        $this->resources();
        $this->components();
        $this->fragments();
        $this->widgets();
        $this->templates();
        $this->menus();
    }

    /**
     * Set media conversions for resources.
     *
     * @return
     */
    protected function conversions()
    {
        $this->app->afterResolving('conversion', function ($manager) {
            $manager->extend('article', function () {
                return new \Armincms\Conversion\CommonConversion;
            });
            $manager->extend('podcast', function () {
                return new \Armincms\Conversion\CommonConversion;
            });
            $manager->extend('post', function () {
                return new \Armincms\Conversion\CommonConversion;
            });
            $manager->extend('video', function () {
                return new \Armincms\Conversion\CommonConversion;
            });
        });
    }

    /**
     * Register the application's Nova resources.
     *
     * @return void
     */
    protected function resources()
    {
        LaravelNova::resources([
            Nova\Article::class,
            Nova\Podcast::class,
            Nova\Post::class,
            Nova\Video::class,
        ]);
    }

    /**
     * Register the application's Gutenberg components.
     *
     * @return void
     */
    protected function components()
    {
        Gutenberg::components([
            Cypress\Blog::class,
        ]);
    }

    /**
     * Register the application's Gutenberg fragments.
     *
     * @return void
     */
    protected function fragments()
    {
        Gutenberg::fragments([
            Cypress\Fragments\Article::class,
            Cypress\Fragments\Podcast::class,
            Cypress\Fragments\Post::class,
            Cypress\Fragments\Video::class,
        ]);
    }

    /**
     * Register the application's Gutenberg widgets.
     *
     * @return void
     */
    protected function widgets()
    {
        Gutenberg::widgets([
            Cypress\Widgets\BlogArchive::class,
            Cypress\Widgets\BlogCategory::class,
            Cypress\Widgets\BlogTag::class,
            Cypress\Widgets\ArticlesCard::class,
            Cypress\Widgets\PodcastsCard::class,
            Cypress\Widgets\PostsCard::class,
            Cypress\Widgets\VideosCard::class,
            Cypress\Widgets\SingleArticle::class,
            Cypress\Widgets\SinglePodcast::class,
            Cypress\Widgets\SinglePost::class,
            Cypress\Widgets\SingleVideo::class,
        ]);
    }

    /**
     * Register the application's Gutenberg templates.
     *
     * @return void
     */
    protected function templates()
    {
        Gutenberg::templates([
            \Armincms\Blogger\Gutenberg\Templates\BlogArchiveWidget::class,
            \Armincms\Blogger\Gutenberg\Templates\BlogCardWidget::class,
            \Armincms\Blogger\Gutenberg\Templates\IndexArticle::class,
            \Armincms\Blogger\Gutenberg\Templates\IndexPodcast::class,
            \Armincms\Blogger\Gutenberg\Templates\IndexPost::class,
            \Armincms\Blogger\Gutenberg\Templates\IndexVideo::class,
            \Armincms\Blogger\Gutenberg\Templates\SingleArticleWidget::class,
            \Armincms\Blogger\Gutenberg\Templates\SinglePodcastWidget::class,
            \Armincms\Blogger\Gutenberg\Templates\SinglePostWidget::class,
            \Armincms\Blogger\Gutenberg\Templates\SingleVideoWidget::class,
        ]);
    }

    /**
     * Register the application's menus.
     *
     * @return void
     */
    protected function menus()
    {
        $this->app->booted(function () {
            $menus = array_unique(array_merge((array) config('nova-menu.menu_item_types'), [
                Menus\Article::class,
                Menus\Podcast::class,
                Menus\Post::class,
                Menus\Video::class,
            ]));

            app('config')->set('nova-menu.menu_item_types', $menus);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            \Illuminate\Console\Events\ArtisanStarting::class,
            \Laravel\Nova\Events\ServingNova::class,
        ];
    }
}
