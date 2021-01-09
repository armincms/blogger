<?php

namespace Armincms\Blogger\Models;

use Armincms\Blogger\Blog;   

class Post extends Blog  
{ 

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function($query) {
        	$query->resource(\Armincms\Blogger\Nova\Post::class);
        });
    }
}
