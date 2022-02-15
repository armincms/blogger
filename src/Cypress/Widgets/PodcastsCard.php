<?php

namespace Armincms\Blogger\Cypress\Widgets; 

class PodcastsCard extends Card
{        
    /**
     * Get resource name.
     * 
     * @return string
     */
    public static function resourceName()
    {
    	return \Armincms\Blogger\Nova\Podcast::class;
    }
}
