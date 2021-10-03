<?php

namespace Armincms\Blogger\Cypress\Widgets; 

use Armincms\Taggable\Cypress\Widgets\SingleTag;

class BlogTag extends SingleTag
{       
    /**
     * Get the category related content template name.
     * 
     * @return string
     */
    public static function contentTemplateName(): string
    {
        return \Armincms\Blogger\Gutenberg\Templates\IndexPost::class;
    } 

    /**
     * Get the related model.
     * 
     * @param  string $relationship 
     * @return string
     */
    protected static function relationModel(string $relationship): string
    { 
        return \Armincms\Blogger\Models\Post::class;
    }
}
