<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Asset $asset)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Asset $asset)
    {
        //
    }

    public function delete(User $user, Asset $asset)
    {
        //
    }

    public function restore(User $user, Asset $asset)
    {
        //
    }

    public function forceDelete(User $user, Asset $asset)
    {
        //
    }
}