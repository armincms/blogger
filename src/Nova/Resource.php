<?php

namespace Armincms\Blogger\Nova;

use Armincms\Categorizable\Nova\Category;
use Armincms\Contract\Nova\Authorizable;
use Armincms\Contract\Nova\Fields;
use Armincms\Fields\Targomaan;
use Armincms\Taggable\Nova\Tag as NovaTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Query\Search\SearchableRelation;
use Laravel\Nova\Resource as NovaResource;

abstract class Resource extends NovaResource
{
    use Authorizable;
    use Fields;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Blog';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__(static::resourceName().' ID'), 'id')->sortable(),

            Targomaan::make([
                Select::make(__(static::resourceName().' Status'), 'marked_as')
                    ->options($this->statuses($request))
                    ->required()
                    ->rules('required')
                    ->default('draft')
                    ->displayUsingLabels(),

                Text::make(__(static::resourceName().' Name'), 'name')->required()->rules('required'),

                Text::make(__(static::resourceName().' Slug'), 'slug')->nullable(),

                Text::make(__(static::resourceName().' Source URL'), 'source')
                    ->required()
                    ->rules('required', 'url')
                    ->canSee(fn ($request) => static::hasSource($request)),
            ]),

            // TODO: have to sync for other languages
            Tag::make(__('Categoires'), 'categories', Category::class)->showCreateRelationButton()->required()->rules('required'),
            Tag::make(__('Tags'), 'tags', NovaTag::class)->showCreateRelationButton()->required()->rules('required'),

            Targomaan::make([
                $this->resourceImage(__(static::resourceName().' Featured Image')),

                Textarea::make(__(static::resourceName().' Summary'), 'summary')->nullable(),

                $this->resourceEditor(__(static::resourceName().' Content'), 'content'),

                Hidden::make('resource')->onlyOnForms()->withMeta(['value' => get_called_class()]),
            ]),

            Panel::make(__('Advanced '.static::resourceName().' Configurations'), [
                Targomaan::make([
                    $this->resourceMeta(__(static::resourceName().' Meta')),
                ]),
            ]),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fieldsForIndex(Request $request)
    {
        $model = static::newModel();

        return [
            ID::make(__(static::resourceName().' ID'), 'id')->sortable(),

            Text::make(__(static::resourceName().' Name'), 'name'),

            $this->resourceUrls(),

            Badge::make(__(static::resourceName().' Status'), 'marked_as')
                ->map([
                    $model->getPublishValue() => 'success',
                    $model->getDraftValue() => 'info',
                    $model->getArchiveValue() => 'warning',
                    $model->getPendingValue() => 'danger',
                ])
                ->labels([
                    $model->getPublishValue() => __($model->getPublishValue()),
                    $model->getDraftValue() => __($model->getDraftValue()),
                    $model->getArchiveValue() => __($model->getArchiveValue()),
                    $model->getPendingValue() => __($model->getPendingValue()),
                ]),
        ];
    }

    /**
     * Get the page statuses.
     *
     * @param  Request  $request
     * @return array
     */
    public function statuses(Request $request)
    {
        $model = static::newModel();

        return $this->filter([
            $model->getDraftValue() => __('Store '.Str::lower(static::resourceName()).' as draft'),

            $this->mergeWhen($request->user()->can('publish', $model), [
                $model->getPublishValue() => __('Publish the '.Str::lower(static::resourceName())),
            ], [
                $model->getPendingValue() => __('Request '.Str::lower(static::resourceName()).' publishing'),
            ]),

            $this->mergeWhen($request->user()->can('archive', $model), [
                $model->getArchiveValue() => __('Archive the '.Str::lower(static::resourceName())),
            ]),
        ]);
    }

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableColumns()
    {
        return ['id', new SearchableRelation('translations', 'name')];
    }

    /**
     * Get the name of the resource.
     *
     * @return string
     */
    protected static function resourceName()
    {
        return Str::title(Str::snake(class_basename(get_called_class()), ' '));
    }

    /**
     * Determine if resource nedd source.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return bool
     */
    public function hasSource($request)
    {
        return in_array(static::class, [Video::class, Article::class, Podcast::class]);
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->localize();
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {
        return parent::relatableQuery($request, $query);
    }
}
