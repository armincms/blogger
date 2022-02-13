<?php

namespace Armincms\Blogger\Gutenberg\Templates; 
 
use Zareismail\Gutenberg\Variable;

class BlogArchive extends Template 
{       
     /**
     * The logical group associated with the template.
     *
     * @var string
     */
    public static $group = 'Blog';

    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [ 
            Variable::make('sorts', __('Array of available sorting')),

            Variable::make('directions', __('Array of sort directions')),

            Variable::make('items', __('HTML generated of blog items')), 

            Variable::make('pagination', __('HTML generated of pagination links')), 
        ];
    } 
}
