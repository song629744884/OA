<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $table = 'menu';
    protected $primaryKey = 'id';
    //public $incrementing = false;
    public $timestamps = false;
    //protected $dateFormat = 'U';//自定义时间戳的格式

    protected $appends = ['status_str'];

    public function getStatusStrAttribute()
    {
        return $this->status==1?'开启':'禁用';
    }
}
