<?php

namespace Armincms\Blogger\Cypress\Fragments; 

class Article extends Blog 
{      
    /**
     * Get the resource Model class.
     * 
     * @return
     */
    public function model(): string
    {
        return \Armincms\Blogger\Models\Article::class;
    }  
}
