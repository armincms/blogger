<?php

namespace Armincms\Blogger\Gutenberg\Templates; 
 
use Zareismail\Gutenberg\Variable;

class IndexPodcast extends Template 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [ 
            Variable::make('id', __('Podcast Id')),

            Variable::make('name', __('Podcast Name')),

            Variable::make('summary', __('Podcast Summary')),

            Variable::make('url', __('Podcast URL')),

            Variable::make('hits', __('Podcast Hits')),

            Variable::make('creation_date', __('Podcast Creation Date')),

            Variable::make('last_update', __('Podcast Update Date')),

            Variable::make('author', __('Podcast Author')),  

            Variable::make('image.templateName', __(
                'Image with the required template (example: image.common-main)'
            ))
        ];
    } 
}
