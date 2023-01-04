<?php

namespace Armincms\Blogger\Cypress\Widgets;

class VideosCard extends Card
{
    /**
     * Get resource name.
     *
     * @return string
     */
    public static function resourceName()
    {
        return \Armincms\Blogger\Nova\Video::class;
    }
}
