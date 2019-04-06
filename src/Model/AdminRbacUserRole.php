<?php
namespace Liudian\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class AdminRbacUserRole extends Model
{

    const UPDATED_AT = null;

    protected $table = 'admin_rbac_user_roles';

    protected $fillable = ['user_id', 'role_id'];
}