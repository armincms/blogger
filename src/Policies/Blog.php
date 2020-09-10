<?php

namespace Armincms\Blogger\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Core\User\Models\User;
use Armincms\Blogger\Blog as Model;

class Blog
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any blogs.
     *
     * @param  \Core\User\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the blog.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function view(User $user, Model $blog)
    {
        return true;
    }

    /**
     * Determine whether the user can create blogs.
     *
     * @param  \Core\User\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the blog.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function update(User $user, Model $blog)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the blog.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function delete(User $user, Model $blog)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the blog.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function restore(User $user, Model $blog)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the blog.
     *
     * @param  \Core\User\Models\User  $user
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function forceDelete(User $user, Model $blog)
    {
        return true;
    }

    /**
     * Determine whether the user can publish the blog.
     *
     * @param  \Core\User\Models\User  $user 
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function publish(User $user, Model $blog)
    {
        return true;
    }

    /**
     * Determine whether the user can archive the blog.
     *
     * @param  \Core\User\Models\User  $user 
     * @param  \Armincms\Blogger\Blog  $blog
     * @return mixed
     */
    public function archive(User $user, Model $blog)
    {
        return true;
    }
}
