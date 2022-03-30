<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class ArticleValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'title'  =>  'require|max:30',
        'content'  =>  'require',
        'type_id'  =>  'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'title.require' => '请填写标题',
        'content.require' => '内容不能为空',
        'type_id.require' => '请选择分类',
    ];
}
