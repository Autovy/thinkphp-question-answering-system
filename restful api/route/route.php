<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

// 注册资源路由，这是restful的基础
// 相当于Route::resource('question','question/Question');
// 注意路由要有一层相同，然后另一层不同，否则路由会混乱
Route::get('question/get', 'question/Question/index');
Route::get('question/read/:id', 'question/Question/read');
Route::post('question/insert', 'question/Question/save');
Route::delete('question/delete/:id', 'question/Question/delete');




return [

];
