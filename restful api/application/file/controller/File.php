<?php


namespace app\file\controller;
use app\file\model\File as FileModel;


class File

{
    /*
     * 文件链接列表（get）:
     * 返回：文件路径列表
     */
    public function get(){

        $file = FileModel::field('name,url')->select();
        return json(['code'=> 200,'data' => $file, 'msg' => '请求成功']);

    }






    /*
     * 文件上传接口（post）：http://127.0.0.1:8000/file/upload/
     * 发送：word文件
     * 返回：提示信息
     */
    public function upload(){

        // 获取表单（请求字段名为doc）上传文件
        $file = request()->file('doc');
        // 检测文件并上传
        // 保留原文件名，且设置为不覆盖
        $info = $file -> validate(['ext'=>'doc,docx'])->move('../file/','',true,false);

        //上传成功
        if($info){
            // 将文件路径信息存入数据库
            // 处理文件名字符串
            $name = str_replace('.docx','',$info->getFilename());

            $data = [
                'name'=> $name,
                'url'=> 'http://127.0.0.1:8000/file/'.$info->getFilename(),
                'creat_time'=> date('Y/m/d H:i:s')
            ];

            $file_data = FileModel::create($data);

            if($file_data){
                return json(['code'=> 200, 'file'=> $info->getFilename() , 'msg'=> '文件上传成功']);
            }


        }

        else{

            return json(['code'=> 400, 'msg'=> $file->getError()]);
        }


    }

}