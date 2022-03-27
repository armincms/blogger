<?php

namespace Armincms\Blogger\Cypress\Fragments;
 
use Armincms\Contract\Concerns\InteractsWithModel; 
use Armincms\Contract\Contracts\Resource; 
use Zareismail\Cypress\Fragment; 
use Zareismail\Cypress\Contracts\Resolvable; 

abstract class Blog extends Fragment implements Resolvable, Resource
{   
    use InteractsWithModel; 

    /**
     * Apply custom query to the given query.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyQuery($request, $query)
    {
        return $query->unless(\Auth::guard('admin')->check(), function($query) {
            return $query->published();
        });
    } 
}
