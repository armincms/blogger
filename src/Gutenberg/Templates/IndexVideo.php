<?php

namespace Armincms\Blogger\Gutenberg\Templates; 

use Zareismail\Gutenberg\Template; 
use Zareismail\Gutenberg\Variable;

class IndexVideo extends Template 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [ 
            Variable::make('id', __('Video Id')),

            Variable::make('name', __('Video Name')),

            Variable::make('summary', __('Video Summary')),

            Variable::make('url', __('Video URL')),

            Variable::make('hits', __('Video Hits')),

            Variable::make('creation_date', __('Video Creation Date')),

            Variable::make('last_update', __('Video Update Date')),

            Variable::make('author', __('Video Author')),  

            Variable::make('image.templateName', __(
                'Image with the required template (example: image.common-main)'
            ))
        ];
    } 
}
