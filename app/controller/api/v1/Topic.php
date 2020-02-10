<?php
declare (strict_types = 1);

namespace app\controller\api\v1;

use think\Request;
use app\lib\BaseController;
use app\model\Topic as TopicModel;
use app\validate\TopicClassValidate;

class Topic extends BaseController
{
    public function index()
    {
        $list = (new TopicModel())->getHotTopicList();
        return self::resCode('success', ['list'=>$list]);
    }

    // 获取指定话题下的文章
    public function post()
    {
        (new TopicClassValidate())->goCheck();
        $list=(new TopicModel())->getPost();
        return self::resCode('获取成功',['list'=>$list]);
    }
}
