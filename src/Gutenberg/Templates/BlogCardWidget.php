<?php

namespace Armincms\Blogger\Gutenberg\Templates; 
 
use Zareismail\Gutenberg\Variable;

class BlogCardWidget extends Template 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [ 
            Variable::make('items', __('HTML generated of blog items')), 

            Variable::make('readmore_url', __('The readmore link url')), 
        ];
    } 
}
