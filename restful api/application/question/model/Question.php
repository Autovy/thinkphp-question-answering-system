<?php


namespace app\question\model;
use think\Model;

// 引入数据库tp_question
class Question extends Model
{

    // 设置插入字段
    protected $field = [

        'id' => 'int',
        'question' , 'score','opa','opb','opc','opc'

    ];


    //与附表构建一对一关系
    public function op()
    {
        return $this->hasOne('Options');
    }

}