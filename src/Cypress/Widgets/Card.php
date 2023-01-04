<?php

namespace Armincms\Blogger\Cypress\Widgets;

use Armincms\Blogger\Models\Blog;
use Armincms\Categorizable\Nova\Category;
use Armincms\Contract\Gutenberg\Widgets\BootstrapsTemplate;
use Armincms\Contract\Gutenberg\Widgets\ResolvesDisplay;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Gutenberg\GutenbergWidget;

abstract class Card extends GutenbergWidget
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
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @param  \Zareismail\Cypress\Layout  $layout
     * @return void
     */
    public function boot(CypressRequest $request, $layout)
    {
        parent::boot($request, $layout);

        $resource = static::resourceName();
        $template = $this->bootstrapTemplate($request, $layout, $this->metaValue($resource::uriKey()));

        $this->displayResourceUsing(function ($attributes) use ($template) {
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
        $handler = 'Armincms\\Blogger\\Gutenberg\\Templates\\Index'.class_basename($resource);

        return [
            Select::make(__('Display '.$resource::label().' By'), 'config->'.$resource::uriKey())
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

            Select::make(__('Readmore page'), 'config->readmore_url')->options(
                Gutenberg::cachedWebsites()->forHandler(\Armincms\Blogger\Cypress\Blog::class)->flatMap->fragments->keyBy->getUrl()->map->name)
                ->required()
                ->rules('required'),

            Number::make(__('Number of resources'), 'config->count')
                ->default(1)
                ->min(1)
                ->required()
                ->rules('required', 'min:1')
                ->help(__('Number of items that should be display.')),

            MultiSelect::make(__('Choose some categories'), 'config->categories')
                ->options(Category::newModel()->get()->keyBy->getKey()->mapInto(Category::class)->map->title())
                ->displayUsingLabels()
                ->required()
                ->rules('required'),
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
            'items' => $this->items()->map(function ($item) {
                return $this->displayResource(
                    $item->serializeForWidget($this->getRequest(), false), static::resourceName()
                );
            })->implode(''),
            'readmore_url' => $this->metaValue('readmore_url'),
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
            \Armincms\Blogger\Gutenberg\Templates\BlogCardWidget::class
        );
    }

    /**
     * Get resource name.
     *
     * @return string
     */
    abstract public static function resourceName();

    protected function items()
    {
        return Blog::when($this->metaValue('categories'), function ($query) {
            $query->whereHas('categories', function ($query) {
                $query->whereKey((array) $this->metaValue('categories'));
            });
        })
            ->when($this->metaValue('direction') == 'desc', function ($query) {
                $query->oldest($this->metaValue('ordering'));
            }, function ($query) {
                $query->latest($this->metaValue('ordering'));
            })
            ->limit(intval($this->metaValue('count')) ?: 3)
            ->with('categories')
            ->get();
    }
}
