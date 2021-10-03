<?php

namespace Armincms\Blogger\Cypress\Widgets; 

class SinglePost extends Single
{         
    /**
     * Get the template name.
     * 
     * @return string
     */
    public static function templateName(): string
    {
        return \Armincms\Blogger\Gutenberg\Templates\SinglePost::class;
    }
}
