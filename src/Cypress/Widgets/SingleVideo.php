<?php

namespace Armincms\Blogger\Cypress\Widgets; 

class SingleVideo extends Single
{         
    /**
     * Get the template name.
     * 
     * @return string
     */
    public static function templateName(): string
    {
        return \Armincms\Blogger\Gutenberg\Templates\SingleVideo::class;
    }
}
