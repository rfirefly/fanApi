<?php
declare (strict_types = 1);

namespace app\validate;

use app\validate\BaseValidate;

class UserValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'phone'=>'require|mobile',
        'code'=>'require|number|length:4|isRight',
        'username'=>'require',
        'password'=>'require|alphaDash',
        'provider'=>'require',
        'openid'=>'require',
        'nickName'=>'require',
        'avatarUrl'=>'require',
        'expires_in'=>'require',
        'id'=>'require|integer|>:0',
        'page'=>'require|integer|>:0',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'phone.require'=>'请填写手机号码',
        'phone.mobile'=>'请正确填写手机号码',
        'code.require'=>'请填写验证码',
        'code.number'=>'请正确填写验证码',
        'code.length'=>'请正确填写验证码',
    ];

    protected $scene = [
        'sendCode'=>['phone'],
        'phoneLogin'=>['phone', 'code'],
        'login'=>['username','password'],
        'thirdLogin'=>['provider','openid','nickName','avatarUrl','expires_in'],
        'post'=>['id','page'],
        'allPost'=>['page']
    ];
}
