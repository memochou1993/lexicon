<?php

namespace App\Models;

use App\Models\Traits\HasTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection $tokens
 * @property Collection $roles
 * @property Collection $teams
 */
class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens, HasTokens {
        HasTokens::tokens insteadof HasApiTokens;
    }
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set the user's password.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * The roles that belong to the user.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get all of the teams that are assigned this user.
     *
     * @return MorphToMany
     */
    public function teams(): MorphToMany
    {
        return $this->morphedByMany(Team::class, 'model', 'model_has_users')
            ->withPivot([
                'is_owner',
            ]);
    }

    /**
     * Get all of the projects that are assigned this user.
     *
     * @return MorphToMany
     */
    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'model', 'model_has_users')
            ->withPivot([
                'is_owner',
            ]);
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  Role|array|string  $roles
     * @return bool
     */
    public function hasRole(...$roles): bool
    {
        return collect($roles)
            ->flatten()
            ->some(function ($role) {
                $role = is_string($role)
                    ? Role::query()->firstWhere('name', $role)
                    : $role;

                return $this->roles->contains($role);
            });
    }

    /**
     * Determine if the user has the given permission.
     *
     * @param  Permission|array|string  $permissions
     * @return bool
     */
    public function hasPermission(...$permissions): bool
    {
        return collect($permissions)
            ->flatten()
            ->some(function ($permission) {
                /** @var Permission $permission */
                $permission = is_string($permission)
                    ? Permission::query()->where('name', $permission)->firstOrNew()
                    : $permission;

                return $this->hasRole($permission->roles);
            });
    }
}
