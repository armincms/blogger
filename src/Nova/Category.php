<?php

namespace Armincms\Blogger\Nova;
 
use Illuminate\Http\Request;
use Armincms\Categorizable\Nova\Category as Resource;


class Category extends Resource
{   
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Blog';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Blogger\Models\Category::class; 
}
