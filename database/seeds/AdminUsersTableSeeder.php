<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \Liudian\Admin\Model\AdminUser::create([
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'nickname' => '超级管理员',
            'status' => 1,
            'supper_admin' => 1
        ]);

        echo "超级管理员登录账号：admin 密码：123456\n";
    }
}
