<?php

namespace Armincms\Blogger\Nova\Fields;

use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Armincms\Nova\Fields\Images as Field;

class Images extends Field
{ 
    protected function handleMedia(NovaRequest $request, $model, $attribute, $data)
    {
        return parent::handleMedia($request, $model, Str::before($attribute, '::'), $data); 
    }
}