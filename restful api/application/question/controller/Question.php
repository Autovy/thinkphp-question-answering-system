<?php

namespace app\question\controller;

use app\question\model\Options;
use think\Controller;
use think\Request;
// 为了方便辨认，将question模型命名为QuestionModel
use app\question\model\Question as QuestionModel;

class Question extends Controller
{

    /**
     * 构造方法依赖注入
     * 查看所有题目接口(get)：http://127.0.0.1:8000/question/get/
     *
     * @return \think\Response
     */
    public function index()
    {
        // 使用预加载查询方法，with调用主模型的op方法关联附表
        // 返回后可以通过关联输出方法处理关联属性（隐藏追加显示等）
        $list = QuestionModel::with('op')->select();
        $list = $list->hidden(['op.question_id']);
        return $list;
    }


    /**
     * 构造方法依赖注入
     * 查看指定题目接口(get) :http://127.0.0.1:8000/question/read/$id/
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        // 使用预加载查询方法，with调用主模型的op方法关联附表
        // 返回后可以通过关联输出方法处理关联属性（隐藏追加显示等）
        $list = QuestionModel::with('op')->select();
        $list = $list->hidden(['op.question_id']);
        return $list[$id-1];
    }




    /**
     * 构造方法依赖注入
     * 插入数据（post）：http://127.0.0.1:8000/question/insert/
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 调用requset的param方法得到post过来的数据
        $data = $request->param();

        // 把post请求的数据插入数据库中（注意数据库的设置）
        // 主表插入
        $question = new QuestionModel();
        $question->question = $data['question'];
        $question->score = $data['score'];
        $question->save();

        //附表插入
        if($question->save()){

            $option = new Options();
            $option->opa = $data['opa'];
            $option->opb = $data['opb'];
            $option->opc = $data['opc'];
            $option->opd = $data['opd'];
            $question->op()->save($option);
            return $this->index();
         }

        else{

            $question->getError();

        }


    }



    /**
     * 构造方法依赖注入
     * 删除指定资源(delete):http://127.0.0.1:8000/question/delete/$id
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $question = QuestionModel::get($id);

        // 删除主表数据
        if($question->delete()){

            // 删除关联数据
            $question->op->delete();
            return $this->index();

        }

        else{

            $question->getError();

        }


    }
}