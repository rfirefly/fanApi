<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class TopicClass extends Model
{
    public function getTopicClassList()
    {
        return $this->field('id, classname as TopicClass')->where('status', 1)->select()->toArray();
    }

    public function topic()
    {
        return $this->hasMany('Topic');
    }

    public function getTopicList()
    {
        // 获取所有参数
        $param = request()->param();
        return self::find($param['id'])->topic()->where('type', 1)->page($param['page'],10)->select();
    }
}
