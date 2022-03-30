<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    //
    protected $name = 'user';
    protected $sexDict = [
        0=>'女',
        1=>'男',
        2=>'未知'

    ];
    protected $statusDict = [
        0=>'禁用',
        1=>'正常',

    ];

    public function getSexStrAttr($value,$data){
        return $this->sexDict[$data['sex']];
    }
    public function getStatusStrAttr($value,$data){
        return $this->statusDict[$data['status']];
    }

    public function departStr()
    {
        return $this->belongsTo(Depart::class, 'depart');
    }
}
