<?php

namespace EasyPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'permissions',
    ];
    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {

        $this->setConnection(config('easy_panel.database.connection'));
        $this->setTable(config('easy_panel.database.roles_table'));

        parent::__construct($attributes);
    }

    public function hasPermission($permission): bool
    {
        if ($this->is_super_admin()) {
            return true;
        }
        foreach ($this->permissions as $userPermission) {
            if ($permission == $userPermission) {
                return true;
            }
        }
        return false;
    }

    /**
     * check if current rule is super admin role
     *
     * @return bool
     */
    public function is_super_admin(): bool
    {
        return $this->name == 'super_admin';
    }

    /**
     * user relation
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        $userModel = config()->has('easy_panel.user_model') ? config('easy_panel.user_model') : config('auth.providers.users.model');
        return $this->belongsToMany($userModel, config('easy_panel.database.roles_users_table'), 'user_id', 'role_id');
    }


}
