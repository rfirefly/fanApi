<?php
declare (strict_types = 1);

namespace app\controller\api\v1;

use think\Request;
use app\lib\BaseController;
use app\model\Topic as TopicModel;
use app\model\Post as PostModel;
use app\model\User as UserModel;
use app\validate\SearchValidate;

class Search extends BaseController
{
    // 搜索话题
    public function topic()
    {
        // 验证keyword和分页数
        (new SearchValidate())->goCheck();
        $list=(new TopicModel())->search();
        return self::resCode('获取成功',['list'=>$list]);
    }

    // 搜索文章
    public function post()
    {
        // 验证keyword和分页数
        (new SearchValidate())->goCheck();
        $list=(new PostModel())->search();
        return self::resCode('获取成功',['list'=>$list]);
    }

    // 搜索用户
    public function user(){
        (new SearchValidate())->goCheck();
        $list = (new UserModel())->Search();
        return self::resCode('获取成功',['list'=>$list]);
    }
}
