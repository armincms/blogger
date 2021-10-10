<?php

namespace Armincms\Blogger\Nova\Fields;

use Illuminate\Support\Str;
use PhoenixLib\NovaNestedTreeAttachMany\Domain\Relation\RelationHandlerFactory;
use PhoenixLib\NovaNestedTreeAttachMany\NestedTreeAttachManyField as Field;

class Category extends Field
{    
    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, 'categories', \Armincms\Categorizable\Nova\Category::class);

        $this->useAsField(); 

        $this->fillUsing(function($request, $model, $attribute, $requestAttribute)  {
            return function() use ($request, $model, $attribute) {   
                $locale = explode('::', $attribute)[1] ?? $attribute; 
                $categories = json_decode($request->{$attribute}, true);
 
                if ($model->locale === $locale) { 
                    $model->categories()->sync($categories);
                } elseif ($model = $model->translations()->where(compact('locale'))->first()) { 
                    $model->categories()->sync($categories);
                }  
            }; 
        }); 

        $this->resolveUsing(function($value, $resource, $attribute)  {
            $locale = explode('::', $attribute)[1] ?? $attribute;   
            $resource->translations->loadMissing('categories');
 
            if ($resource->locale === $locale) { 
                return $resource->categories->modelKeys();
            } elseif ($model = $resource->translations->where('locale', $locale)->first()) {   
                return $model->categories->modelKeys();
            } 

            return [];
        }); 
    }
}
