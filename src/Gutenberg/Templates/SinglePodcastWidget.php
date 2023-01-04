<?php

namespace Armincms\Blogger\Gutenberg\Templates;

use Zareismail\Gutenberg\Variable;

class SinglePodcastWidget extends SinglePostWidget
{
    /**
     * Register the given variables.
     *
     * @return array
     */
    public static function variables(): array
    {
        return array_merge(parent::variables(), [
            Variable::make('source', __('Podcast Source URL')),
        ]);
    }
}
