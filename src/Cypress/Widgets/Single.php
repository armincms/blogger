<?php

namespace Armincms\Blogger\Cypress\Widgets;
 
use Laravel\Nova\Fields\Select;
use Zareismail\Cypress\Widget;  
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Gutenberg\HasTemplate;

abstract class Single extends Widget
{       
    use HasTemplate;

    /**
     * Indicates if the widget should be shown on the component page.
     *
     * @var \Closure|bool
     */
    public $showOnComponent = false;

    /**
     * Bootstrap the resource for the given request.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @param  \Zareismail\Cypress\Layout $layout 
     * @return void                  
     */
    public function boot(CypressRequest $request, $layout)
    {   
        $this->bootstrapTemplate($request, $layout);

        $this->withMeta([
            'resource' => $request->resolveFragment()->metaValue('resource')
        ]);
    }

    /**
     * Get the template id.
     * 
     * @return integer
     */
    public function getTemplateId(): int
    {
        return $this->metaValue('template');
    } 

    /**
     * Serialize the widget fro template.
     * 
     * @return array
     */
    public function serializeForTemplate(): array
    {
        $request = $this->getRequest();

        return $request->resolveFragment()->metaValue('resource')->serializeForWidget($request);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function fields($request)
    {
        return [
            Select::make(__('Display Blog Template'), 'config->template')
                ->options(static::availableTemplates(static::templateName()))
                ->displayUsingLabels()
                ->required()
                ->rules('required'),
        ];
    }

    /**
     * Get the template name.
     * 
     * @return string
     */
    abstract public static function templateName(): string;
}
