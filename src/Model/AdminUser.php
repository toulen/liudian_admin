<?php
namespace Liudian\Admin\Model;

use Illuminate\Database\Eloquent\Model;
use Liudian\Admin\Foundation\AuthPermission;

class AdminUser extends Model
{
    use AuthPermission;

    protected $table = 'admin_users';

    protected $fillable = ['username', 'nickname', 'password', 'supper_admin', 'phone', 'email', 'head_img', 'default_route'];

    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($password){
        if($password) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function roleIds(){
        return $this->hasMany(AdminRbacUserRole::class, 'user_id', 'id');
    }

    public function roles(){
        return $this->belongsToMany(AdminRbacRole::class, 'admin_rbac_user_roles', 'user_id', 'role_id');
    }
}