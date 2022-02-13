<?php

namespace Armincms\Blogger\Cypress\Widgets;
 
use Armincms\Blogger\Models\Blog; 
use Armincms\Contract\Gutenberg\Templates\Pagination; 
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;  
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Gutenberg\Gutenberg; 
use Zareismail\Gutenberg\GutenbergWidget; 

class BlogArchive extends GutenbergWidget
{       
    /**
     * The callback to be used to resolve the resourc's display.
     *
     * @var \Closure
     */
    public $displayResourceCallback = []; 

    /**
     * The callback to be used to resolve the pagination's display.
     *
     * @var \Closure
     */
    public $displayPaginationCallback = []; 

    /**
     * Bootstrap the resource for the given request.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @param  \Zareismail\Cypress\Layout $layout 
     * @return void                  
     */
    public function boot(CypressRequest $request, $layout)
    {  
        parent::boot($request, $layout);

        collect(static::resources())->each(function($resource) { 
            if ($this->shouldDisplayResource($resource)) { 
                $template = Gutenberg::cachedTemplates()->find($this->metaValue($resource::uriKey()));
 
                $this->displayResourceUsing($resource, function($attributes) use ($template) {
                    return $template->gutenbergTemplate($attributes)->render();
                });  
            } 
        }); 

        $this->displayPaginationUsing(function($attributes) use ($request, $layout) {
            $paginationTemplate = Gutenberg::cachedTemplates()->find($this->metaValue('pagination'));

            if (is_null($paginationTemplate)) return; 

            $paginationTemplate->plugins
                 ->filter->isActive()
                 ->flatMap->gutenbergPlugins()
                 ->each->boot($request, $layout);

            return $paginationTemplate->gutenbergTemplate($attributes)->render();
        });
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function fields($request)
    { 
        return collect(static::resources())->map(function($resource)  { 
            return Select::make(__('Display '.$resource::label().' By'), 'config->'.$resource::uriKey())
                ->options(static::templates($resource)) 
                ->nullable()
                ->displayUsingLabels()
                ->withMeta([
                    'placeholder' => __('Dont Display '. $resource::label())
                ]); 
        })->merge([   
            Select::make(__('Display Pagination By'), 'config->pagination')
                ->options(Gutenberg::cachedTemplates()->forHandler(Pagination::class)->keyBy->getKey()->map->name)  
                ->displayUsingLabels()
                ->required()
                ->rules('required'), 

            Number::make(__('Per page'), 'config->per_page')
                ->default(1)
                ->min(1)
                ->required()
                ->rules('required', 'min:1')
                ->help(__('How many item should be display on each page.')),  
        ])->toArray();
    } 

    /**
     * Serialize the widget fro display.
     * 
     * @return array
     */
    public function serializeForDisplay(): array
    { 
        $resources = collect(static::resources())->filter(function($resource) {
            return $this->shouldDisplayResource($resource);
        });

        $blogs = Blog::resources($resources->values()->all())
            ->published()
            ->when(request('direction') == 'asc', function($query) {
                $query->latest(request('ordering'));
            }, function($query) {
                $query->oldest(request('ordering'));
            }) 
            ->paginate($this->metaValue('per_page'));

        return [
            'items' => $blogs->getCollection()->map(function($item) {  
                $attributes = $item->serializeForIndexWidget($this->getRequest(), false);

                return $this->displayResource($item->resource, $attributes);
            })->implode(''),

            'pagination' => $this->displayPagination($blogs->toArray()),

            'sorts' => [
                'created_at' => __('Creation date'),
                'updated_at' => __('Update date'),
                'hits' => __('Hits'),
            ],

            'directions' => [
                'asc' => __('Ascending'),
                'desc' => __('Descending'),  
            ],
        ];
    }

    /**
     * Define the callback that should be used to display the pagination.
     *
     * @param  callable  $displayPaginationCallback
     * @return $this
     */
    public function displayPaginationUsing(callable $displayPaginationCallback)
    {
        $this->displayPaginationCallback = $displayPaginationCallback;

        return $this;
    }

    public function displayPagination($attributes = [])
    { 
        return call_user_func($this->displayPaginationCallback, $attributes);
    } 

    /**
     * Define the callback that should be used to display the resources.
     *
     * @param  string   $resource
     * @param  callable $displayResourceCallback
     * @return $this
     */
    public function displayResourceUsing(string $resource, callable $displayResourceCallback)
    {
        $this->displayResourceCallback[$resource] = $displayResourceCallback;

        return $this;
    } 

    public function displayResource($resource, $attributes)
    {
        $callback = $this->displayResourceCallback[$resource];

        return is_callable($callback) ? call_user_func($callback, $attributes) : '';
    } 

    /**
     * Query related tempaltes.
     * 
     * @param  [type] $request [description]
     * @param  [type] $query   [description]
     * @return [type]          [description]
     */
    public static function relatableTemplates($request, $query)
    {
        return $query->handledBy(
            \Armincms\Blogger\Gutenberg\Templates\BlogArchive::class
        );
    } 

    /**
     * Determins if the resouce should display.
     * 
     * @param  string $resource 
     * @return bool           
     */
    public function shouldDisplayResource($resource)
    {
        return $this->metaValue('resources.'.$resource::uriKey());
    }

    /**
     * Get the available resource.
     * 
     * @return array
     */
    public static function resources()
    {
        return [ 
            \Armincms\Blogger\Nova\Article::class,
            \Armincms\Blogger\Nova\Podcast::class,
            \Armincms\Blogger\Nova\Post::class,
            \Armincms\Blogger\Nova\Video::class,
        ];
    }

    /**
     * Get the available resource.
     * 
     * @return array
     */
    public static function templates($resource)
    { 
        $handler = 'Armincms\\Blogger\\Gutenberg\\Templates\\Index'. class_basename($resource); 

        return Gutenberg::cachedTemplates()->forHandler($handler)->keyBy->getKey()->map->name;
    }
}
