<?php

namespace Armincms\Blogger\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel; 
use Laravel\Nova\Fields\{ID, Text, Textarea}; 
use Armincms\Nova\Resource as BaseResource;
use Armincms\Fields\{Targomaan, BelongsToMany};
use Whitecube\NovaFlexibleContent\Flexible;
use Outhebox\NovaHiddenField\HiddenField;
use Inspheric\Fields\Url;

abstract class Resource extends BaseResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Armincms\Blogger\Blog';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Blog';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query
                    ->where('language', app()->getLocale())
                    ->where('resource', static::class);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(), 

            new Targomaan([ 
                HiddenField::make('resource')
                    ->defaultValue(static::class)
                    ->onlyOnForms(),

                Text::make(__('Title'), 'title')
                    ->required(), 

                $this->slugField(),

                Url::make('URL')
                    ->readonly()
                    ->hideWhenCreating()
                    ->clickable()
                    ->resolveUsing(function($value, $resource) { 
                        return $resource->url();
                    })
                    ->fillUsing(function() {})
            ]),

            $this->when(static::class === Article::class, Flexible::make(__('References'), 'source')
                ->collapsed()
                ->button(__('Add Reference'))
                ->addLayout(__('Source'), 'source', [
                    Text::make(__('Title'), 'title')
                        ->required(),

                    Text::make(__('Author'), 'author')
                        ->required(),

                    Url::make(__('Source'), 'source')
                        ->required(),
                ]) 
            ), 

            $this->when($this->hasSource(),Url::make(__('Source'), 'source')->required()), 

            BelongsToMany::make(__('Categories'), 'categories', Category::class)
                ->hideFromIndex(),

            BelongsToMany::make(__('Tags'), 'tags', Tag::class)
                ->hideFromIndex(), 

            (new Targomaan([ 
                $this->abstractField(), 

                $this->gutenbergField(), 
            ]))->withoutToolbar(),

            new Panel(__('Media'), [
                (new Targomaan([
                    $this->imageField(),
                ]))->withoutToolbar(),
            ]),

            $this->when($request->isMethod('put') || $request->isMethod('post'),     
                Text::make('async')->fillUsing(function($request, $model) {  
                    $model::saved(function($saved) use ($model) { 
                        if($saved->is($model)) { 
                            $categories = $model->categories()->get();
                            $tags = $model->tags()->get(); 

                            $model->translations()->get()->each(function($trans) use ($model, $categories, $tags) {
                                $trans->categories()->sync($categories);
                                $trans->tags()->sync($tags);
                            });  
                        }
                    });
                }),
            ),
        ];
    }

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    { 
        return with(parent::newModel(), function($model) {
            return $model->forceFill(['resource' => class_basename(static::class)]);
        });
    } 

    public function hasSource()
    {
        return in_array(static::class, [Video::class, Podcast::class]);
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
