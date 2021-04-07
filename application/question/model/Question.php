<?php


namespace app\question\model;
use think\Model;

// 引入数据库tp_question
class Question extends Model
{
    // 构建一对一关系
    public function op()
    {
        return $this->hasOne('Options');
    }

}