<?php
namespace app\controller;

use app\lib\BaseController;
use app\lib\BaseException;
use app\validate\BaseValidate;
// use think\Request;

class Index extends BaseController
{
    public function index()
    {
        return \json(
            [
                'msg' => 'ok',
                'list' => [
                    'list' => 9999
                ]
            ]
        );
    }

    public function getData()
    {
        $list = [
            ["name"=>"lili", "age"=>19],
            ["name"=>"yun", "age"=>18]
        ];
        return self::resCodeWithoutData("获取成功");
    }

    public function check()
    {
        // new BaseException();
        (new BaseValidate())->goCheck();
        return 11;
    }

    public function getinfo()
    {
        halt(phpinfo());
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
