<?php 

namespace Armincms\Blogger;

use Illuminate\Support\Str; 

class Locate
{ 
    public static function moduleLocales()
    {
        return collect([
                Nova\Post::class, Nova\Video::class, Nova\Podcast::class, Nova\Article::class
            ])->map(function($resource) {
                return [
                    'title' => $resource::label(),
                    'name'  => Str::singular($resource::uriKey()),
                    'id'    => '*',
                    'childrens' => $resource::newModel()->resource($resource)->get()->mapInto($resource)->map(function($resource) { 
                        return [
                            'title' => $resource->title() ?? $resource->getKey(), 
                            'name'  => Str::singular($resource::uriKey()),
                            'id'    => $resource->getKey(),
                            'url'   => $resource->url(),
                        ];
                    })->toArray(),
                ];
            })->push([
                'title' => Nova\Category::label(),
                'name'  => 'category',
                'id'    => '*',
                'childrens' => Nova\Category::newModel()->whereDoesntHave('parent')->with('subCategories')->get()->mapInto(Nova\Category::class)->map([static::class, 'categoryInformation'])->toArray()
            ])->values()->toArray();
    }

    public static function categoryInformation($category)
    {
        $childrens = $category->subCategories->mapInto(Nova\Category::class)->map([
            static::class, 'categoryInformation'
        ]);

        return array_filter([
            'title' => $category->title() ?? $category->getKey(), 
            'name'  => Str::kebab(class_basename($category::$model)),
            'id'    => $category->getKey(),
            'childrens' => $childrens->isEmpty() ? null : $childrens->all(),
            'url'   => app('site')->get('blog')->url(urldecode($category->url)),
        ]);
    }
}