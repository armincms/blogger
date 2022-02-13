<?php

namespace Armincms\Blogger\Cypress\Widgets;
 
use Armincms\Blogger\Models\Blog; 
use Armincms\Contract\Gutenberg\Templates\Pagination; 
use Armincms\Contract\Gutenberg\Widgets\BootstrapsTemplate; 
use Armincms\Contract\Gutenberg\Widgets\ResolvesDisplay; 
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;  
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Gutenberg\Gutenberg; 
use Zareismail\Gutenberg\GutenbergWidget; 


abstract class Favorite extends GutenbergWidget
{       
    use BootstrapsTemplate;
    use ResolvesDisplay;

    /**
     * The callback to be used to resolve the pagination's display.
     *
     * @var \Closure
     */
    public $displayPaginationCallback = []; 

    /**
     * The logical group associated with the widget.
     *
     * @var string
     */
    public static $group = 'Blog';

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

        $resource = static::resourceName();
        $template = $this->bootstrapTemplate($request, $layout, $this->metaValue($resource::uriKey()));
 
        $this->displayResourceUsing(function($attributes) use ($template) {   
            return $template->gutenbergTemplate($attributes)->render();
        }, static::resourceName()); 
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function fields($request)
    { 
        $resource = static::resourceName();
        $handler = 'Armincms\\Blogger\\Gutenberg\\Templates\\Index'. class_basename($resource);

        return [ 
            Select::make(__('Display '.$resource::label().' By'), 'config->'. $resource::uriKey())
                ->options(Gutenberg::cachedTemplates()->forHandler($handler)->keyBy->getKey()->map->name)
                ->required()
                ->rules('required')
                ->displayUsingLabels(),

            Select::make(__('Sort '.$resource::label().' By'), 'config->ordering')
                ->options([
                    'created_at' => __('Creation Date'),
                    'updated_at' => __('Update Date'),
                    'hits' => __('Number of hits'),
                ])
                ->required()
                ->rules('required')
                ->default('created_at'),

            Select::make(__('Sort '.$resource::label().' As'), 'config->direction')
                ->options([
                    'asc' => __('Ascending'),
                    'desc' => __('Descending'), 
                ])
                ->required()
                ->rules('required')
                ->default('asc'),

            Number::make(__('Number of resources'), 'config->count')
                ->default(1)
                ->min(1)
                ->required()
                ->rules('required', 'min:1')
                ->help(__('Number of items that should be display.')), 
        ];
    } 

    /**
     * Serialize the widget fro display.
     * 
     * @return array
     */
    public function serializeForDisplay(): array
    {  
        return [
            'items' => $this->displayResource([], static::resourceName()),  
        ];
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
            \Armincms\Blogger\Gutenberg\Templates\BlogFavorite::class
        );
    }   

    /**
     * Get resource name.
     * 
     * @return string
     */
    abstract public static function resourceName();
}
