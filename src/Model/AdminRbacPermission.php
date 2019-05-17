<?php
namespace Liudian\Admin\Model;


use Liudian\Admin\Baum\Node;

class AdminRbacPermission extends Node
{

    protected $table = 'admin_rbac_permissions';

    protected $leftColumn = 'left_key';

    protected $rightColumn = 'right_key';

    protected $fillable = ['name', 'route_name', 'nav_show', 'icon'];

    protected $guarded = ['id', 'parent_id', 'left_key', 'right_key', 'depth'];
}