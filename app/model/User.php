<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\facade\Cache;

/**
 * @mixin think\Model
 */
class User extends Model
{
    protected $autoWriteTimestamp = true;

    // 验证第三方登录是否绑定手机
    public function isBindPhone($param){
        // 验证是否是第三方登录
        if(array_key_exists('type',$param)){
            if($param['user_id']<1) \ApiException('请先绑定手机', 40005, 200);
            return $param['user_id'];
        }
        // 账号密码登录
        return $param['id'];
    }

    // 绑定用户第三方信息表
    public function userinfo(){
        return $this->hasOne('Userinfo');
    }

    // 绑定用户信息表
    public function userbind(){
        return $this->hasMany('UserBind');
    }

     // 关联文章
    public function post(){
        return $this->hasMany('Post');
    }

    // 生成并保存token
    public function createSaveToken($arr=[]){
        // 生成token
        // \halt(microtime(true));
        $token = sha1(md5(uniqid(md5(microtime()),true)));
        $arr['token'] = $token;
        // 登录过期时间
        $expire =array_key_exists('expires_in',$arr) ? $arr['expires_in'] : config('api.token_expire');
        // 保存到缓存中
        if (!Cache::set($token,$arr,$expire)) \ApiException('token缓存失败', 90001, 206);;
        // 返回token
        return $token;
    }

    // 用户是否被禁用
    public function checkStatus($arr, $thirdLogin=false){
        if($thirdLogin){

            $userId = array_key_exists('user_id',$arr)?$arr['user_id']:$arr['id'];
            if(!$userId) return $arr;
            // 查询user表
            $user = $this->find($userId)->toArray();
            // 拿到status
            $status = $user['status'];
        }else{
            $status = $arr['status'];
        }
        if($status==0) \ApiException('用户已禁用', 40001, 202);;
        return $arr;
    }

    // 验证密码
    public function checkPassword($password,$hash){
        if (!$hash) \ApiException('未设置密码', 40002, 202);
        // 密码错误
        if(!password_verify($password,$hash)) \ApiException('密码错误', 20001, 200);
        return true;
    }




    // *****功能模块*****

    // **验证码发送模块**
    public function sendCode()
    {
        $phone = request()->param('phone');
        // 验证码是否已经发送过
        if(Cache::get($phone)){
            \ApiException('验证码已发送', 10001, 200);
        }
        
        $code = random_int(1000,9999);
        Cache::set($phone, $code, config('api.sms_expire'));
        return $code;
    }


    // **手机,验证码登入模块**

    // 判断用户是否存在
    public function isExist($arr=[]){
        if(!is_array($arr)) return false;
        if (array_key_exists('phone',$arr)) { // 通过手机号码获取用户数据
            return $this->where('phone',$arr['phone'])->find();
        }
        // 用户id
        if (array_key_exists('id',$arr)) { // 用户名
            return $this->where('id',$arr['id'])->find();
        }
        if (array_key_exists('email',$arr)) { // 邮箱
            return $this->where('email',$arr['email'])->find();
        }
        if (array_key_exists('username',$arr)) { // 用户名
            return $this->where('username',$arr['username'])->find();
        }
        // 第三方参数
        if (array_key_exists('provider',$arr)) {
            return $this->userbind()->where([
                'type'=>$arr['provider'],
                'openid'=>$arr['openid']
            ])->find();
        }
        return false;
    }

    public function phoneLogin()
    {
        $param = request()->param();
        $user = $this->isExist(['phone'=>$param['phone']]);
        // 若$user为空,则直接创建用户
        if(!$user){
            $user = self::create([
                'phone'  =>  $param['phone'],
                'username' =>  $param['phone'],
                'password' => password_hash($param['phone'], PASSWORD_DEFAULT)
            ]);
            $user->userinfo()->create(['user_id'=>$user->id]);
            return $this->createSaveToken($user->toArray());
        }
        // 用户是否被禁用
        $this->checkStatus($user->toArray());
        // 登录成功，返回token
        return $this->CreateSaveToken($user->toArray());
    }


    // **账号密码登入模块**
    public function filterUserData($data){
        $arr=[];
        // 验证是否是手机号码
        if(preg_match('^1(3|4|5|7|8)[0-9]\d{8}$^', $data)){
            $arr['phone']=$data; 
            return $arr;
        }
        // 验证是否是邮箱
        if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/', $data)){
            $arr['email']=$data; 
            return $arr;
        }
        $arr['username']=$data; 
        return $arr;
    }

    public function login()
    {
        $param = request()->param();
        $user = $this->isExist($this->filterUserData($param['username']));
        if(!$user) \ApiException('昵称/邮箱/手机号错误', 20002, 200);;
        // 用户是否被禁用
        $this->checkStatus($user->toArray());
        // 验证密码
        $this->checkPassword($param['password'],$user->password);
        // 登录成功 生成token，进行缓存，返回客户端
        return $this->CreateSaveToken($user->toArray());
    }


    // **第三方登入模块**
    public function thirdLogin(){
        // 获取所有参数
        $param = request()->param();
        // 解密过程（待添加）
        // 验证用户是否存在
        $user = $this->isExist(['provider'=>$param['provider'],'openid'=>$param['openid']]);
        // 用户不存在，创建用户
        $arr = [];
        if (!$user) {
            $user = $this->userbind()->save([
                'type'=>$param['provider'],
                'openid'=>$param['openid'],
                'nickname'=>$param['nickName'],
                'avatarurl'=>$param['avatarUrl']
            ]);
            $arr = $user->toArray();
            $arr['expires_in'] = $param['expires_in']; 
            return $this->CreateSaveToken($arr);
        }
        // 用户是否被禁用
        $arr = $this->checkStatus($user->toArray(), true);
        // 登录成功，返回token
        $arr['expires_in'] = $param['expires_in']; 
        return $this->CreateSaveToken($arr);
    }

    // 退出模块
    public function logout()
    {
        if(!Cache::pull(request()->userToken)) \ApiException('你已经退出', 10003, 200);
        return true;
    }

    // 获取用户发布文章列表(公开的)
    public function getPostList()
    {
        $param = \request()->param();
        $user = self::find($param['id']);
        if (!$user) \ApiException('用户不存在', 40005, 200);
        // 用户存在，返回公开数据
        return $user->post()->with([
            'user'=>function($query){
                $query->field('id,username,userpic');
            },
            'sharePost',
            'images'=>function($query) {
                $query->field('url');
            }])->where('isopen',1)
            ->hidden(['user_id', 'pivot', 'images'=>['pivot']])
            ->page($param['page'], 10)
            ->select();
    }

    // 获取用户发布全部文章
    public function getAllPostList(){
        $param = \request()->param();
        $userId = \request()->userId;
        return self::find($userId)->post()->with([
            'user'=>function($query){
                $query->field('id,username,userpic');
            },
            'sharePost',
            'images'=>function($query) {
                $query->field('url');
            }])->hidden(['user_id', 'pivot', 'images'=>['pivot']])
            ->page($param['page'], 10)
            ->select();
    }

    // 搜索用户
    public function Search()
    {
        return self::where('username','like','%'.\input('keyword').'%')
        ->hidden(['password'])
        ->page((int)\input('page'), 10)
        ->select();
    }
}
