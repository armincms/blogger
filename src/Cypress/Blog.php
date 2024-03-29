<?php

namespace Armincms\Blogger\Cypress;

use Zareismail\Cypress\Component;
use Zareismail\Cypress\Contracts\Resolvable;

class Blog extends Component implements Resolvable
{
    /**
     * The display layout class name.
     *
     * @var string
     */
    public $layout = \Zareismail\Cypress\Layouts\Clean::class;

    /**
     * Resolve the resoruce's value for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @return void
     */
    public function resolve($request): bool
    {
        return true;
    }

    /**
     * Get the component fragments.
     *
     * @return string
     */
    public function fragments(): array
    {
        return [
        ];
    }
}
