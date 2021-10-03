<?php

namespace Armincms\Blogger\Gutenberg\Templates; 
 
use Zareismail\Gutenberg\Variable;

class SingleVideo extends SinglePost 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return array_merge(parent::variables(), [ 
            Variable::make('source', __('Video Source URL')), 
        ]);
    } 
}
