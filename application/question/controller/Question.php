<?php

namespace app\question\controller;
// 为了方便辨认，将question模型命名为QuestionModel
use app\question\model\Question as QuestionModel;

class Question
{
// 查看所有题目接口
// -------------------------------------------
    // 使用注解路由，简化url，访问url为：http://127.0.0.1:8000/question/get
    /**
     * @return mixed
     * @route('question/get')
     *
     */
    public function get_question(){

        // 使用预加载查询方法，with调用主模型的op方法关联附表
        // 返回后可以通过关联输出方法处理关联属性（隐藏追加显示等）
       $result = QuestionModel::with('op')->select();
       $result= $result->hidden(['op.question_id']);
       return json($result);

    }


// 查看指定题目接口
// -------------------------------------------
    // 使用注解路由，简化url，访问url为：http://127.0.0.1:8000/question/get
    /**
     * @param int $id
     * @return mixed
     * @route('question/:id')
     *
     */
    public function get_oen_question($id){

         //使用预加载查询方法，with调用主模型的op方法关联附表
        $result = QuestionModel::with('op')->select();
        $result= $result->hidden(['op.question_id']);
        return json($result[$id-1]);

//            return QuestionModel::get($id);


    }


}