<?php

namespace Armincms\Blogger\Gutenberg\Templates; 
 
use Zareismail\Gutenberg\Variable;

class SinglePostWidget extends Template 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [ 
            Variable::make('id', __('Post Id')),

            Variable::make('name', __('Post Name')),

            Variable::make('url', __('Post URL')),

            Variable::make('hits', __('Post Hits')),

            Variable::make('creation_date', __('Post Creation Date')),

            Variable::make('last_update', __('Post Update Date')),

            Variable::make('author', __('Post Author')),

            Variable::make('summary', __('Post Summary')),

            Variable::make('content', __('Post Content')),

            Variable::make('image.templateName', __(
                'Image with the required template (example: image.common-main)'
            ))
        ];
    } 
}
