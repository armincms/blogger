<?php

namespace Armincms\Blogger\Models;

class Article extends Blog
{
    /**
     * Get the available media collections.
     *
     * @return array
     */
    public function getMediaCollections(): array
    {
        return [
            'image' => [
                'conversions' => ['article'],
                'multiple' => false,
                'disk' => 'image',
                'limit' => 20, // count of images
                'accepts' => ['image/jpeg', 'image/jpg', 'image/png'],
            ],
        ];
    }
}
