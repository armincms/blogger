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
            })->values()->toArray();
    }
}