<?php

namespace App\Models;

use Spatie\Permission\Models\Permission;

/**
 * Class Role
 *
 * @property Permission[] $permissions
 * @property string $name
 * @package App\Laravue\Models
 */
class Role extends \Spatie\Permission\Models\Role
{
    public $guard_name = 'api';
    /**
     * Check whether current role is supper administrator
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->name === Acl::ROLE_SUPER_ADMIN;
    }
    /**
     * Check whether current role is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === Acl::ROLE_ADMIN;
    }
}
