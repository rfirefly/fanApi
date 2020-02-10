<?php
declare (strict_types = 1);

namespace app\controller\api\v1;

use think\Request;
use app\lib\BaseController;
use app\model\PostClass as PostClassModel;

class PostClass extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = (new PostClassModel())->getPostClassList();
        return self::resCode('success', ['list'=>$list]);
    }
}
