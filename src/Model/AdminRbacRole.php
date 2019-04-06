<?php
namespace Liudian\Admin\Model;

use Baum\Node;

class AdminRbacRole extends Node
{

    protected $table = 'admin_rbac_roles';

    protected $leftColumn = 'left_key';

    protected $rightColumn = 'right_key';

    protected $fillable = ['name'];

    protected $guarded = ['id', 'parent_id', 'left_key', 'right_key', 'depth'];

    public function permissionIds(){
        return $this->hasMany(AdminRbacRolePermission::class, 'role_id', 'id');
    }

    public function permissions(){
        return $this->belongsToMany(AdminRbacPermission::class, (new AdminRbacRolePermission())->getTable(), 'role_id', 'permission_id');
    }
}