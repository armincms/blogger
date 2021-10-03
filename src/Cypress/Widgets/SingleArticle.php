<?php

namespace Armincms\Blogger\Cypress\Widgets; 

class SingleArticle extends Single
{         
    /**
     * Get the template name.
     * 
     * @return string
     */
    public static function templateName(): string
    {
        return \Armincms\Blogger\Gutenberg\Templates\SingleArticle::class;
    }
}
