# LiudianAdmin模块
### 安装：
`composer require 'toulen/liudian_admin'`

### 使用

##### 1.生成配置文件和静态文件
`php artisan vendor:publish`
#### 重要：
如果需要自定义，请修改liudian/admin目录下的控制器

##### 2.生成数据表
###### 在.env中设置了数据库连接之后，运行：
`php artisan migrate --path=vendor/toulen/liudian_admin/database/migrations`
###### 即可生成数据表
##### 3.生成初始管理员
`php artisan db:seed --class=AdminUsersTableSeeder`
`php artisan db:seed --class=RbacTableSeeder`
##### 4.生成storage的软连接
`php artisan storage:link`
##### 5.访问
`http://xxx.xx/admin/index`
