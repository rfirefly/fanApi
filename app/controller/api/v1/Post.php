<?php
declare (strict_types = 1);

namespace app\controller\api\v1;

use think\Request;
use app\lib\BaseController;
use app\model\Post as PostModel;
use app\validate\PostValidate;

class Post extends BaseController
{
    public function create()
    {
        (new PostValidate())->goCheck('create');
        (new PostModel()) -> createPost();
        return self::resCodeWithOutData('发布成功');
    }

    // 获取文章详情
    public function index()
    {
        // 验证文章id
        (new PostValidate())->goCheck('detail');
        $detail = (new PostModel()) -> getPostDetail();
        return self::resCode('获取成功',['detail'=>$detail]);
    }
}
