<?php

namespace Armincms\Blogger\Policies;

use Armincms\Contract\Policies\Policy;
use Armincms\Contract\Policies\SoftDeletes;

class Blog extends Policy
{
    use SoftDeletes;

    /**
     * Determine whether the user can publish the blog.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function publish($user, $model)
    {
    }

    /**
     * Determine whether the user can archive the blog.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function archive($user, $model)
    {
    }
}
