<?php

namespace Armincms\Blogger\Gutenberg\Templates; 
 
use Zareismail\Gutenberg\Variable;

class IndexArticle extends Template 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return [ 
            Variable::make('id', __('Article Id')),

            Variable::make('name', __('Article Name')),

            Variable::make('summary', __('Article Summary')),

            Variable::make('url', __('Article URL')),

            Variable::make('hits', __('Article Hits')),

            Variable::make('creation_date', __('Article Creation Date')),

            Variable::make('last_update', __('Article Update Date')),

            Variable::make('author', __('Article Author')),  

            Variable::make('image.templateName', __(
                'Image with the required template (example: image.common-main)'
            ))
        ];
    } 
}
