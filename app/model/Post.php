<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Post extends Model
{
    protected $autoWriteTimestamp = true;

    // 关联分享文章
    public function sharePost()
    {
        return $this->belongsTo(Post::class, 'share_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 关联图片表
    public function images(){
        return $this->belongsToMany(Image::class, 'post_image');
    }

    public function createPost()
    {
         // 获取所有参数
        $params = request()->param();
        // 获取用户id
        $user_id = request()->userId;
        $currentUser = User::find($user_id);
        $path = $currentUser->userinfo->path;
        // 发布文章
        $title = mb_substr($params['text'],0,30);
        $post = $this->create([
            'user_id'=>$user_id,
            'title'=>$title,
            'titlepic'=>'',
            'content'=>$params['text'],
            'path'=>$path ? $path : '未知',
            'type'=>0,
            'post_class_id'=>$params['post_class_id'],
            'share_id'=>0,
            'isopen'=>$params['isopen']
        ]);
        // 关联图片
        $imglistLength = count($params['imgList']);
        if($imglistLength > 0){
            $ImageModel = new Image();
            $imgidarr = [];
            for ($i=0; $i < $imglistLength; $i++) { 
                if ($ImageModel->isImageExist($params['imgList'][$i]['id'],$user_id)) {
                    $imgidarr[] = $params['imgList'][$i]['id'];
                }
            }
            // 发布关联
            if(count($imgidarr)) $post->images()->attach($imgidarr, ['create_time'=>time()]);
        }
        // 返回成功
        return true;
    }

    // 获取文章细节
    public function getPostDetail(){
        $params = \request()->param();
        return self::with([
            'sharePost',
            'user'=>function($query) {
                $query->field('id,username,userpic');
            },
            'images'=>function($query) {
                $query->field('url');
            }]) ->find($params['id'])
                ->hidden(['user_id', 'images'=>['pivot']]);

    }

    // 搜索文章，根据keyword
    public function search()
    {
        return self::where('title','like','%'.\input('keyword').'%')
        ->where('isopen',1)
        ->with([
            'sharePost',
            'user'=>function($query) {
                $query->field('id,username,userpic');
            },
            'images'=>function($query) {
                $query->field('url');
            }])
        ->page((int)\input('page'), 10)
        ->select()
        ->hidden(['user_id', 'images'=>['pivot']]);
    }
}
