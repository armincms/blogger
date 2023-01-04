<?php

namespace Armincms\Blogger\Cypress\Fragments;

class Podcast extends Blog
{
    /**
     * Get the resource Model class.
     *
     * @return
     */
    public function model(): string
    {
        return \Armincms\Blogger\Models\Podcast::class;
    }
}
