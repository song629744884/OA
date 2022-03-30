<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Menu extends Model
{
    //
    protected $name = 'menu';

    public function getStatusStrAttr($value,$data){
        $status = [1=>'正常',2=>'禁用'];
        return $status[$data['status']];
    }
}
