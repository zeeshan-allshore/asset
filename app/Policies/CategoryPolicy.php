<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Category $category)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Category $category)
    {
        //
    }

    public function delete(User $user, Category $category)
    {
        //
    }

    public function restore(User $user, Category $category)
    {
        //
    }

    public function forceDelete(User $user, Category $category)
    {
        //
    }
}