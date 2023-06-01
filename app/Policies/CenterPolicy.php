<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Center;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CenterPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Center $center)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Center $center)
    {
        //
    }

    public function delete(User $user, Center $center)
    {
        //
    }

    public function restore(User $user, Center $center)
    {
        //
    }

    public function forceDelete(User $user, Center $center)
    {
        //
    }
}