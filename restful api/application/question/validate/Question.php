<?php


namespace app\question\validate;

// 设置验证规则，用于验证接口的数据
use think\Validate;

class Question extends Validate
{
    protected $rule = [

        'id'=>'require|integer',
    ];

    protected $message  =   [

        'id.require'=>'缺乏必要id',
        'id.integer'=>'请求格式错误'
    ];
}