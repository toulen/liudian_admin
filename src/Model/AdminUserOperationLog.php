<?php
namespace Liudian\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class AdminUserOperationLog extends Model
{

    const CREATED_AT = 'operation_at';

    const UPDATED_AT = null;

    protected $table = 'admin_user_operation_logs';

    protected $fillable = ['admin_user_id', 'operation_name', 'target_class', 'target_id', 'operation_intro', 'operation_data'];

    public function adminUser(){
        return $this->hasOne(AdminUser::class, 'id', 'admin_user_id');
    }
}