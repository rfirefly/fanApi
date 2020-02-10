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
use think\facade\Route;

Route::group('api/:version', function () {
    // 发送验证码
    Route::post('/user/sendCode', 'api.:version.User/sendCode');
    // 手机，验证码登入
    Route::post('/user/phoneLogin', 'api.:version.User/phoneLogin');
    // 账号，密码登入
    Route::post('/user/login','api.:version.User/login');
    // 第三方登入
    Route::post('/user/thirdLogin','api.:version.User/thirdLogin');
    // 获取文章分类列表
    Route::get('/postClass','api.:version.PostClass/index');
    // 获取话题分类列表
    Route::get('/TopicClass','api.:version.TopicClass/index');
    // 获取热门话题列表
    Route::get('/HotTopic','api.:version.Topic/index');
    // 获取话题列表
    Route::get('/TopicClass/:id/topic/:page','api.:version.TopicClass/Topic');
    // 获取文章详情
    Route::get('post/:id', 'api.:version.Post/index');
    // 获取指定话题下的文章列表
    Route::get('topic/:id/post/:page', 'api.:version.Topic/post');
    // 获取指定话题分类下的文章
    Route::get('postclass/:id/post/:page', 'api.:version.TopicClass/post');
    // 获取指定用户下的文章
    Route::get('user/:id/post/:page', 'api.:version.User/post');
    // 搜索话题
    Route::post('search/topic', 'api.:version.Search/topic');
    // 搜索文章
    Route::post('search/post', 'api.:version.Search/post');
    // 搜索用户
    Route::post('search/user', 'api.:version.Search/user');
});


Route::group('api/:version', function () {
    // 退出登入
    Route::post('/user/logout','api.:version.User/logout');
})->middleware(['ApiAuth']);


Route::group('api/:version', function () {
    // 退出登入
    Route::post('/image/uploadMore','api.:version.Image/uploadMore');
    // 创建文章
    Route::post('/post/create','api.:version.Post/create');
    // 获取用户发布全部文章
    Route::get('user/post/:page', 'api.:version.User/allPost');
})->middleware(['ApiAuth', 'ApiUserStatus', 'ApiUserBindPhone']);

Route::get('hello/:name', 'index/hello');
