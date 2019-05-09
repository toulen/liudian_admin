<?php

use Illuminate\Database\Seeder;

class RbacTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 添加权限列表
        $root = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '系统设置',
            'route_name' => 'admin_index',
            'nav_show' => 1
        ]);
        $root->makeRoot();

        $adminIndexObj = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '管理首页',
            'route_name' => 'admin_index',
            'nav_show' => 1
        ]);
        $adminIndexObj->makeChildOf($root);

        $adminAccountIndex = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '后台管理员列表',
            'route_name' => 'admin_account_index',
            'nav_show' => 1
        ]);
        $adminAccountIndex->makeChildOf($root);

        $adminAccountCreate = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '创建管理员',
            'route_name' => 'admin_account_create',
            'nav_show' => 0
        ]);
        $adminAccountCreate->makeChildOf($adminAccountIndex);

        $adminAccountEdit = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '编辑管理员资料',
            'route_name' => 'admin_account_edit',
            'nav_show' => 0
        ]);
        $adminAccountEdit->makeChildOf($adminAccountIndex);

        $adminAccountDelete = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '删除管理员',
            'route_name' => 'admin_account_delete',
            'nav_show' => 0
        ]);
        $adminAccountDelete->makeChildOf($adminAccountIndex);

        $adminAccountEditPassword = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '修改管理员密码',
            'route_name' => 'admin_account_edit_password',
            'nav_show' => 0
        ]);
        $adminAccountEditPassword->makeChildOf($adminAccountIndex);

        $adminAccountRoles = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '管理员分组',
            'route_name' => 'admin_account_roles',
            'nav_show' => 0
        ]);
        $adminAccountRoles->makeChildOf($adminAccountIndex);

        $adminRoleIndex = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '后台管理员分组',
            'route_name' => 'admin_role_index',
            'nav_show' => 1
        ]);
        $adminRoleIndex->makeChildOf($root);

        $adminRoleCreate = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '新增管理员分组',
            'route_name' => 'admin_role_create',
            'nav_show' => 0
        ]);
        $adminRoleCreate->makeChildOf($adminRoleIndex);

        $adminRoleEdit = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '编辑管理员分组',
            'route_name' => 'admin_role_edit',
            'nav_show' => 0
        ]);
        $adminRoleEdit->makeChildOf($adminRoleIndex);

        $adminRoleDelete = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '删除管理员分组',
            'route_name' => 'admin_role_delete',
            'nav_show' => 0
        ]);
        $adminRoleDelete->makeChildOf($adminRoleIndex);

        $adminRolePermmsions = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '分组权限管理',
            'route_name' => 'admin_role_permissions',
            'nav_show' => 0
        ]);
        $adminRolePermmsions->makeChildOf($adminRoleIndex);

        $adminPermissionIndex = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '后台菜单管理',
            'route_name' => 'admin_permission_index',
            'nav_show' => 1
        ]);
        $adminPermissionIndex->makeChildOf($root);

        $adminPermissionCreate = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '新增菜单',
            'route_name' => 'admin_permission_create',
            'nav_show' => 0
        ]);
        $adminPermissionCreate->makeChildOf($adminPermissionIndex);

        $adminPermissionEdit = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '编辑菜单',
            'route_name' => 'admin_permission_edit',
            'nav_show' => 0
        ]);
        $adminPermissionEdit->makeChildOf($adminPermissionIndex);

        $adminPermissionDelete = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '删除菜单',
            'route_name' => 'admin_permission_delete',
            'nav_show' => 0
        ]);
        $adminPermissionDelete->makeChildOf($adminPermissionIndex);

        $adminPermissionMove = \Liudian\Admin\Model\AdminRbacPermission::create([
            'name' => '移动菜单',
            'route_name' => 'admin_permission_move',
            'nav_show' => 0
        ]);
        $adminPermissionMove->makeChildOf($adminPermissionIndex);
    }
}
