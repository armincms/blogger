<?php

namespace Armincms\Blogger\Cypress\Widgets; 

class SinglePodcast extends Single
{         
    /**
     * Get the template name.
     * 
     * @return string
     */
    public static function templateName(): string
    {
        return \Armincms\Blogger\Gutenberg\Templates\SinglePodcast::class;
    }
}
