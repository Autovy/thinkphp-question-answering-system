<?php

namespace app\question\controller;

use app\question\model\Options;
use think\Controller;
use think\exception\ErrorException;
use think\Request;
// 为了方便辨认，将question模型命名为QuestionModel
use app\question\model\Question as QuestionModel;
use app\question\model\Options as QptionsModel;

class Question extends Controller
{

    /**
     *
     * 查看所有题目接口(get)：http://127.0.0.1:8000/question/get/
     *
     * @return \think\Response
     */
    public function index()
    {
        // 使用预加载查询方法，with调用主模型的op方法关联附表
        // 返回后可以通过关联输出方法处理关联属性（隐藏追加显示等）
        $list = QuestionModel::with('op')->select();
        $list = $list->hidden(['op.question_id','op.answer']);

        // 异常处理
        if(is_null($list)){
           return json(['data'=>'NULL','code'=> 200]);
        }

        else{
            return json(['code'=> 200,'data'=>$list]);
        }

    }


    /**
     *
     * 查看指定题目接口(get) :http://127.0.0.1:8000/question/read/$id/
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {

        // 异常处理
        // 设置验证器
        $validate = new \app\question\validate\Question;


        // 验证器只能验证关联数组形式
            if($validate->check($data=['id'=>$id])){

                // 使用预加载查询方法，with调用主模型的op方法关联附表
                // 返回后可以通过关联输出方法处理关联属性（隐藏追加显示等）
                $list = QuestionModel::with('op')->get($id);
                if(!is_null($list)) {
                    $list = $list->hidden(['op.question_id','op.answer']);
                    return json(['code' => 200, 'data' => $list]);
                }
                else{
                    return json(['code'=>400,'msg'=>'查无此条目']);
                }
            }

            else{

                return json(['code'=> 400,'msg'=>$validate->getError()]);

            }


    }




    /**
     * 操作方法依赖注入
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
        // 异常捕捉
        try{
            // 主表插入
            $question = new QuestionModel();
            $question->question = $data['question'];
            $question->score = $data['score'];

            //附表插入

            $option = new Options();
            $option->opa = $data['opa'];
            $option->opb = $data['opb'];
            $option->opc = $data['opc'];
            $option->opd = $data['opd'];
            $option->answer = $data['answer'];
            $question->save();
            $question->op()->save($option);
            return json(['code'=> 201,'msg'=>'创建成功']);
        }
        catch (ErrorException $error){
            return json(['code'=> 400,'msg'=> $error->getMessage()]);
        }



    }



    /**
     *
     * 删除指定资源(delete):http://127.0.0.1:8000/question/delete/$id
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $question = QuestionModel::get($id);

        // 异常捕捉
        if(!is_null($question)){

            // 删除主表数据
            if($question->delete()){

                // 删除关联数据
                $question->op->delete();
                return json(['code'=> 200,'msg'=>'删除成功']);

            }

            }

        else{
            return json(['code'=> 400, 'msg'=>'未找到删除对象']);
        }

    }

    /**
     * 答案验证接口
     * 插入数据（post）：http://127.0.0.1:8000/question/verify/
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function verify(Request $request)
    {
        // 获得用户答卷
        $num = 0;
        $scoreall = 0;
        $data = $request->param();


        // 验证答案（post的字段为题目q+id的格式发送请求）
        foreach ($data as $key=>$datum){


            $obj = QuestionModel::field('id,score')->select();
            $id = str_replace('q','',$key);

            // 查找答案，异常处理不存在的题目
            try {
                $answer = $this->answer($id);
            }
            catch (ErrorException $error){
                return json(['code'=> 400, 'msg'=>'题目不存在']);
            }

            // 答案正确时
            if($datum==$answer){
                $scoreall += $obj[$num]['score'];
            }
            $num++;

        }

        return json(['code'=> 200, 'score'=>$scoreall ,'msg'=>'分数验证成功']);




    }

    // 私有方法，供上面verify方法调用
    private function answer($id){
        // 获得题目答案，和分数
        $answer = QuestionModel::get($id)->op->answer;
        return $answer;
    }


}