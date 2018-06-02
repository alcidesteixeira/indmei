<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Relationship with sample articles
     */
    public function sampleArticles()
    {
        return $this->hasMany('App\SampleArticle');
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                abort(401, 'O seu Role não permite aceder a esta área.');
        }
        return $this->hasRole($roles) ||
            abort(401, 'O seu Role não permite aceder a esta área.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('role_id', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('role_id', $role)->first();
    }

    /**
     * Get users and Roles associated
     */
    public function getAllUser() {

        $users = User::all();
        //dd($users);

        foreach ($users as $user) {
            $roles = User::find(1)->roles()->orderBy('name')->get();
        }

        return $users;

    }
}
