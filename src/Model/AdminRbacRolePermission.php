<?php
namespace Liudian\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class AdminRbacRolePermission extends Model
{

    const UPDATED_AT = null;

    protected $table = 'admin_rbac_role_permissions';

    protected $fillable = ['permission_id', 'role_id'];
}