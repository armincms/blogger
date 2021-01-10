<?php

namespace Armincms\Blogger\Models;
    
use Armincms\Categorizable\Category as Model;
use Core\HttpSite\Component; 
use Armincms\Blogger\Blog;

class Category extends Model  
{    
    /**
     * Get the interface of scoped resources.
     * 
     * @return string
     */
    public static function resourcesScope() : string
    {
        return Blog::class;
    }

    public function component() : Component
    {
    	return new \Armincms\Blogger\Components\Category;
    }
}
