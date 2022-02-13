<?php

namespace Armincms\Blogger\Cypress\Widgets; 

use Armincms\Taggable\Cypress\Widgets\SingleTag;

class BlogTag extends SingleTag
{        
    /**
     * The logical group associated with the widget.
     *
     * @var string
     */
    public static $group = 'Blog';

    /**
     * Get the related model.
     * 
     * @param  string $relationship 
     * @return string
     */
    protected static function relationModel(string $relationship): string
    { 
        return \Armincms\Blogger\Models\Blog::class;
    }
  
    /**
     * Get the tag related content template name.
     * 
     * @return string
     */
    public static function resources(): array
    {
        return [
            \Armincms\Blogger\Nova\Article::class,
            \Armincms\Blogger\Nova\Podcast::class,
            \Armincms\Blogger\Nova\Post::class,
            \Armincms\Blogger\Nova\Video::class,
        ];
    } 
  
    /**
     * Get the template handlers for given resourceName.
     * 
     * @return string
     */
    public static function handler(string $resourceName): array
    {
        return [
            'Armincms\\Blogger\\Gutenberg\\Templates\\Index'. class_basename($resourceName)
        ];
    } 

    /**
     * Apply custom query to the relationship query.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query        
     * @param  string  $relationship 
     * @return \Illuminate\Database\Eloquent\Builder                
     */
    protected function applyRelationshipQuery($query, $relationship)
    {
        $resources = collect($this->resources())->filter(function($resource) {
            return $this->metaValue($resource::uriKey());
        });

        return $query->resources($resources->values()->all());
    }

    /**
     * Get resource for the given model.
     * 
     * @param  \Illuminate\Database\Eloqeunt\Model $model 
     * @return string      
     */
    public static function findResourceForModel($model)
    {
        return $model->resource;
    }
}
