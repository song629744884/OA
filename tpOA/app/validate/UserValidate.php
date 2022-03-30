<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class UserValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name'  =>  'require|max:30',
        'phone'=>'require|length:11',
        'sex'=>'require',
        'status'=>'require',
        'role_id'=>'require',
        'birth'=>'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '请输入姓名',
        'phone.require' => '请输入手机号码',
        'sex.require' => '请选择性别',
        'status.require' => '请选择状态',
        'role_id.require' => '请选择角色',
        'birth.require' => '请选择生日日期',
    ];
}
