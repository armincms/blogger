<?php

namespace Armincms\Blogger\Nova;
 
use Armincms\Categorizable\Nova\Category as Resource; 

class Category extends Resource
{  
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Blog';

    public function categorizables() : array
    {
        return [
            Blog::class,
        ];
    }
}
