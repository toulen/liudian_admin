<?php
namespace Liudian\Admin\Model;

use Illuminate\Database\Eloquent\Model;

class ChinaArea extends Model
{

    protected $timestamps = false;

    protected $table = 'china_area';

    public function children(){

        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent(){

        return $this->hasOne(self::class, 'id', 'parent_id');
    }
}