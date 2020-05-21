<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->tokenCan('view-team');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Team  $team
     * @return bool
     */
    public function view(User $user, Team $team)
    {
        return $user->tokenCan('view-team')
            && $user->hasTeam($team);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->tokenCan('create-team');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Team  $team
     * @return bool
     */
    public function update(User $user, Team $team)
    {
        return $user->tokenCan('update-team')
            && $user->hasTeam($team);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Team  $team
     * @return bool
     */
    public function delete(User $user, Team $team)
    {
        return $user->tokenCan('delete-team')
            && $user->hasTeam($team);
    }
}
