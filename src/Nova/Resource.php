<?php

namespace Armincms\Blogger\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel; 
use Laravel\Nova\Fields\{ID, Text, Textarea, Number, Password, DateTime}; 
use Armincms\Nova\Resource as BaseResource;
use Armincms\Taggable\Nova\Fields\Tags; 
use Armincms\Fields\{Targomaan, BelongsToMany};
use Whitecube\NovaFlexibleContent\Flexible;
use Outhebox\NovaHiddenField\HiddenField;
use OwenMelbz\RadioField\RadioButton;
use Inspheric\Fields\Url;

abstract class Resource extends BaseResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Blogger\Blog::class;

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
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
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

                Url::make(__('Title'), 'url')
                    ->exceptOnForms()
                    ->alwaysClickable() 
                    ->resolveUsing(function($value, $resource, $attribute) {
                        return $this->site()->url(urldecode($value));
                    })
                    ->titleUsing(function($value, $resource) {
                        return $this->title;
                    }) 
                    ->labelUsing(function($value, $resource) {
                        return $this->title;
                    }),
                
                HiddenField::make('resource')
                    ->defaultValue(static::class)
                    ->onlyOnForms(),

                RadioButton::make(__('Mark As'), 'marked_as')
                    ->marginBetween() 
                    ->options(
                        collect([
                            static::getDraftValue()   => __('Draft'), 
                            static::getPublishValue() => __('Publish'),
                            static::getPendingValue() => __('Pending'), 
                        ])->filter(function($mark, $key) use ($request) {
                            return  $key !== static::getPublishValue() || 
                                    $request->user()->can('publish', [$this->resource]);
                        })->all()
                    )
                    ->default(static::getDraftValue()), 

                Text::make(__('Title'), 'title')
                    ->required()
                    ->rules('required')
                    ->onlyOnForms(), 

                $this->slugField(),

                Url::make('URL')
                    ->alwaysClickable()
                    ->hideWhenCreating()
                    ->onlyOnForms()
                    ->readOnly()
                    ->resolveUsing(function($value, $resource, $attribute) {
                        return $this->site()->url(urldecode($value));
                    })
                    ->fillUsing(function() {}),


                Number::make(__('Hits'), 'hits')
                    ->exceptOnForms(),
            ]),

            $this->when(static::class === Article::class, Flexible::make(__('References'), 'source')
                ->collapsed()
                ->hideFromIndex()
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

            $this->when($this->hasSource(), Url::make(__('Source'), 'source')->required()), 

            BelongsToMany::make(__('Categories'), 'categories', Category::class)
                ->hideFromIndex(),

            Tags::make(__('Tags'), 'tags')
                ->hideFromIndex(), 

            (new Targomaan([ 
                $this->abstractField(), 

                $this->gutenbergField(), 
            ]))->withoutToolbar(),

            new Panel(__('Advanced'), [
                new Targomaan([
                    $this->seoField()
                        ->hideFromIndex(),
                ]),

                Password::make(__('Password'), 'password')
                    ->nullable()
                    ->hideFromIndex(),

                DateTime::make(__('Publish Date'), 'publish_date')
                    ->nullable()
                    ->default((string) now())
                    ->hideFromIndex(),

                $this->when(
                    $request->user()->can('archive', [$this->resource]), 
                    DateTime::make(__('Archive Date'), 'archive_date')
                        ->nullable()
                        ->hideFromIndex()
                ), 
            ]),

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
