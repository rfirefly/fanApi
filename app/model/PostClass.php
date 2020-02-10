<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class PostClass extends Model
{
    // 关联文章模型
    public function post(){
        return $this->hasMany(Post::class);
    }

    public function getPostClassList()
    {
        return $this->field('id, classname as postClass')->where('status', 1)->select()->toArray();
    }

    // 获取指定话题分类下的文章
    public function getPost()
    {
        $param = \request()->param();
        return self::find($param['id'])->post()->page($param['page'], 10)->select();
    }
}
