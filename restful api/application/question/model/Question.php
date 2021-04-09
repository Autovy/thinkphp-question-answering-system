<?php


namespace app\question\model;
use think\Model;

// 引入数据库tp_question
// 模型可以对在数据库获得的数据进一步处理
class Question extends Model
{

    // 模型对应数据表的字段列表（非必须）
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