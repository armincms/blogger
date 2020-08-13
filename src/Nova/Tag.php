<?php

namespace Armincms\Blogger\Nova;
 
use Armincms\Taggable\Nova\Tag as Resource; 

class Tag extends Resource
{  
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Blog';

    public function taggables() : array
    {
        return [
            Blog::class,
        ];
    }
}
