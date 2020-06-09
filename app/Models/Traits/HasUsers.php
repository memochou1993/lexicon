<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property Collection $users
 * @property Collection $owners
 */
trait HasUsers
{
    /**
     * Get all of the users for the model.
     *
     * @return MorphToMany
     */
    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'model', 'model_has_users');
    }

    /**
     * @return MorphToMany
     */
    public function owners()
    {
        return $this->users()->where('is_owner', true);
    }

    /**
     * Determine if the model has the given user.
     *
     * @param  User  $user
     * @return bool
     */
    public function hasUser(User $user)
    {
        return $this->users->contains($user);
    }
}
