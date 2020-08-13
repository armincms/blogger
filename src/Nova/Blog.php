<?php

namespace Armincms\Blogger\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Armincms\Categorizable\Nova\Categorizable;


class Blog extends Categorizable
{  
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function fields(Request $request): array
    {
        return [
            Boolean::make('title'),
        ];
    }
}
