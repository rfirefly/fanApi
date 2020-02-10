<?php
declare (strict_types = 1);

namespace app\controller\api\v1;

use app\lib\BaseController;
use app\validate\UserValidate;
use app\model\User as UserModel;

use think\Request;

class User extends BaseController
{
    public function sendCode()
    {
        // 验证手机号
        (new UserValidate())->goCheck('sendCode');
        // 发送验证码
        $code = (new UserModel())->sendCode();
        
        return self::resCode("success", ['code'=>$code]);
    }

    public function phoneLogin()
    {
        (new UserValidate())->goCheck('phoneLogin');
        $token = (new UserModel())->phoneLogin();
        return self::resCode("success", ['token'=>$token]);
    }

    public function login()
    {
        (new UserValidate())->goCheck('login');
        $token = (new UserModel())->login();
        return self::resCode("success", ['token'=>$token]);
    }

    public function thirdLogin(){
        // 验证登录信息
        (new UserValidate())->goCheck('thirdLogin');
        $token = (new UserModel())->thirdLogin();
        return self::resCode('登录成功',['token'=>$token]);
    }

    public function logout()
    {
        (new UserModel())->logout();
        return self::resCodeWithoutData('退出成功');
    }

    // 获取用户发布文章列表
    public function post(){
        (new UserValidate())->goCheck('post'); 
        $list = (new UserModel())->getPostList();
        return self::resCode('获取成功',['list'=>$list]);
    }

    // 获取用户发布全部文章
    public function allPost(){
        (new UserValidate())->goCheck('allPost'); 
        $list = (new UserModel())->getAllPostList();
        return self::resCode('获取成功',['list'=>$list]);
    }


}
