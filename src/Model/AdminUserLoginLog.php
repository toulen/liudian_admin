<?php
namespace Liudian\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class AdminUserLoginLog extends Model
{

    const CREATED_AT = 'login_at';

    const UPDATED_AT = null;

    protected $table = 'admin_user_login_logs';

    protected $fillable = ['admin_id', 'login_ip'];
}