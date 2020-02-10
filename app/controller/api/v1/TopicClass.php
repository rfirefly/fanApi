<?php
declare (strict_types = 1);

namespace app\controller\api\v1;

use think\Request;
use app\lib\BaseController;
use app\model\TopicClass as TopicClassModel;
use app\model\PostClass as PostClassModel;
use app\validate\TopicClassValidate;

class TopicClass extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = (new TopicClassModel())->getTopicClassList();
        return self::resCode('success', ['list'=>$list]);
    }

    // 获取话题列表
    public function Topic()
    {
      (new TopicClassValidate())->goCheck();
      $list = (new TopicClassModel())->getTopicList();
      return self::resCode('success', ['list'=>$list]);
    }

    // 获取指定话题分类下的文章
    public function post()
    {
        // 验证分类id和分页数
        (new TopicClassValidate())->goCheck();
        $list=(new PostClassModel)->getPost();
        return self::resCode('获取成功',['list'=>$list]);
    }
}
