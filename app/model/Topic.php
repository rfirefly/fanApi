<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Topic extends Model
{
    // 关联文章
    public function post(){
        return $this->belongsToMany(Post::class,'topic_post');
    }

    public function getHotTopicList()
    {
        return $this->where('type', 1)->limit(10)->select()->toArray();
    }

    // 获取指定话题下的文章（分页）
    public function getPost()
    {
        // 获取所有参数
        $param = request()->param();
        return self::find($param['id'])->post()->with([
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

    // 搜索指定话题
    public function search()
    {
        return self::where('title','like','%'.\input('keyword').'%')
        ->page((int)\input('page'), 10)
        ->select();
    }
}
