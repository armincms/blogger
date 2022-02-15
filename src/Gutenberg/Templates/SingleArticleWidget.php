<?php

namespace Armincms\Blogger\Gutenberg\Templates; 
 
use Zareismail\Gutenberg\Variable;

class SingleArticleWidget extends SinglePostWidget 
{       
    /**
     * Register the given variables.
     * 
     * @return array
     */
    public static function variables(): array
    {
        return array_merge(parent::variables(), [ 
            Variable::make('source', __('Article Source URL')), 
        ]);
    } 
}
