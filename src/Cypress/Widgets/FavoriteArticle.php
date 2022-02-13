<?php

namespace Armincms\Blogger\Cypress\Widgets; 

class FavoriteArticle extends Favorite
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
