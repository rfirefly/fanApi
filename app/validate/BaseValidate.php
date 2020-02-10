<?php
namespace app\validate;

use think\Validate;
use think\Request;


class BaseValidate extends Validate
{
    public function goCheck($scene='')
    {   
        // 获取响应数据
        $params = request()->param();
        // 数据校验
        $check = $this->scene($scene)->check($params);
        if(!$check){
            // \halt($this->getError());
            \ApiException($this->getError(), 10000, 400);
        }
        return true;
    }

    public function isRight($value, $rule='', $data='', $field='')
    {
        $code = \cache($data['phone']);
        if(!$code) return "验证码已过期，请重新获取";
        if($value != $code) return "验证码错误";
        return true;
    }

    // 话题是否存在
    public function isTopicExist($value, $rule='', $data='', $field='')
    {
        if ($value==0) return true;
        if (\app\model\Topic::field('id')->find($value)) {
            return true;
        }
        return "该话题已不存在";
    }

    // 文章分类是否存在
    protected function isPostClassExist($value, $rule='', $data='', $field='')
    {
        if (\app\model\PostClass::field('id')->find($value)) {
            return true;
        }
        return "该文章分类已不存在";
    }
}
