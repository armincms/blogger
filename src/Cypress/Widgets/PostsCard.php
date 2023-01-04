<?php

namespace Armincms\Blogger\Cypress\Widgets;

class PostsCard extends Card
{
    /**
     * Get resource name.
     *
     * @return string
     */
    public static function resourceName()
    {
        return \Armincms\Blogger\Nova\Post::class;
    }
}
