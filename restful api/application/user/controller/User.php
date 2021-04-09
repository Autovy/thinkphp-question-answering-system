<?php

namespace app\user\controller;

use think\Controller;
use think\Request;
use app\user\model\User as UserModel;

class User extends Controller
{
    /**
     * 显示用户列表:http://127.0.0.1:8000/user/get
     *
     * @return \think\Response
     */
    public function index()
    {

     $result = UserModel::select();
     return json(['code'=> 200,'data'=>$result, 'msg'=>'用户列表请求正常']) ;


    }


    /**
     * 显示指定的用户:http://127.0.0.1:8000/user/read/$id
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $result = UserModel::get($id);
        // 异常验证
        if($result==NULL){
            return json(['code'=> 400,'msg'=>'目标对象不存在']);
        }
        else{
            return json(['code'=> 200,'data'=>$result, 'msg'=>'目标用户请求正常']);
        }

    }


    /**
     * 插入用户信息：http://127.0.0.1:8000/user/insert/
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 从数据库获得名字一列为数组
        $list = UserModel::field('name')->select();
        // 从请求中提取出名字字段
        $name = $request->param('name');

        // 验证名字是否重复
        foreach ($list as $item){
            if($name == $item["name"]){
                return json(['code'=> 400,'msg'=>'该名字已存在']);
            }
        }

        // 插入该用户
        $user = new UserModel();
        $user->name = $name;


        // 插入成功
        if($user->save()){
            return json(['code'=> 200, 'msg'=> '插入成功']);
        }

        else{
            return json(['code'=> 400, 'msg'=> '插入失败']);
        }


    }



    /**
     * 删除用户：http://127.0.0.1:8000/user/delete/$id
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $obj = UserModel::get($id);
        if($obj!=NULL){
            UserModel::destroy($id);
            return json(['code'=> 200, 'msg'=> '删除成功']);
        }
        else{
            return json(['code'=> 400, 'msg'=> '找不到删除对象']);
        }
    }
}
