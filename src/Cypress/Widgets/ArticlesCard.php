<?php

namespace Armincms\Blogger\Cypress\Widgets; 

class ArticlesCard extends Card
{        
    /**
     * Get resource name.
     * 
     * @return string
     */
    public static function resourceName()
    {
    	return \Armincms\Blogger\Nova\Article::class;
    }
}
